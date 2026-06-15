<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\SolicitudTipo;
use App\Models\SolicitudAdjunto;
use App\Models\SolicitudEvento;
use App\Models\RecintoReserva;
use App\Models\Recinto;
use App\Services\SolicitudNotificacionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VecinoController extends Controller
{
    public function index()
    {
        $tipos_solicitud = SolicitudTipo::habilitados()
            ->orderBy('seccion')
            ->orderBy('titulo')
            ->get()
            ->groupBy('seccion');

        return view('vecino.solicitudes.index', compact('tipos_solicitud'));
    }

    public function misSolicitudes(Request $request)
    {
        $query = Solicitud::with(['tipo', 'asignado'])
            ->where('vecino_id', auth()->id());

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('folio')) {
            $query->where('folio', 'like', '%' . $request->folio . '%');
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('vecino.solicitudes.mis-solicitudes', compact('solicitudes'));
    }

    public function showSolicitud($id)
    {
        $solicitud = Solicitud::with(['tipo', 'asignado', 'adjuntos', 'eventos.actor'])
            ->where('vecino_id', auth()->id())
            ->findOrFail($id);

        return view('vecino.solicitudes.show', compact('solicitud'));
    }

    public function iniciarSolicitud($tipoId)
    {
        $tipo = SolicitudTipo::findHabilitadoOrFail($tipoId);
        
        // Cargar recintos filtrados según el tipo de solicitud
        $recintos = collect([]);
        if (in_array('recinto', $tipo->campos_requisitos) || in_array($tipo->codigo, ['RECINTOS_MUNICIPALES', 'RECINTOS_DEPORTIVOS'])) {
            $query = Recinto::where('activo', true);
            $recintosIds = $tipo->recintos_ids ?? [];
            if (! empty($recintosIds)) {
                $query->whereIn('id', $recintosIds);
            } elseif ($tipo->codigo === 'RECINTOS_DEPORTIVOS') {
                $query->where('tipo', 'deportivo');
            } elseif ($tipo->codigo === 'RECINTOS_MUNICIPALES') {
                $query->whereIn('tipo', ['salon', 'teatro', 'espacio_comunitario']);
            }
            $recintos = $query->orderBy('nombre')->get();
        }

        $ciudadano = auth()->user();
        $ciudadano->asegurarIdentidadClaveUnica();
        $datosPrecargados = $ciudadano->datosPrecargadosSolicitud();

        return view('vecino.solicitudes.wizard', compact('tipo', 'recintos', 'datosPrecargados', 'ciudadano'));
    }

    public function storeSolicitud(Request $request, $tipoId)
    {
        $tipo = SolicitudTipo::findHabilitadoOrFail($tipoId);

        $request->validate(
            array_merge([
                'datos' => 'required|array',
                // Solo permitir PDF y JPG/JPEG
                'adjuntos.*' => 'file|mimes:pdf,jpg,jpeg|max:5120', // 5MB max
            ], $tipo->reglasValidacionStore()),
            $tipo->mensajesValidacionStore()
        );

        DB::beginTransaction();
        try {
            // Generar folio
            $year = date('Y');
            $ultimo = Solicitud::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();
            
            $numero = $ultimo ? (int)substr($ultimo->folio, -6) + 1 : 1;
            $folio = 'CHANCO-' . $year . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);

            // Nombre y RUT provienen exclusivamente de Clave Única
            $user = auth()->user();
            $datos = $user->aplicarIdentidadEnDatos($request->datos);

            if (empty($datos['rut'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo obtener su RUT desde Clave Única. Cierre sesión e ingrese nuevamente con ClaveÚnica.',
                ], 422);
            }

            $solicitud = Solicitud::create([
                'folio' => $folio,
                'tipo_id' => $tipoId,
                'vecino_id' => auth()->id(),
                'estado' => 'enviada',
                'datos_json' => $datos,
            ]);

            // Actualizar solo email del vecino (el RUT no se modifica; viene de Clave Única)
            $emailVecino = $request->datos['email'] ?? $request->datos['mail'] ?? null;
            if (!empty($emailVecino) && filter_var($emailVecino, FILTER_VALIDATE_EMAIL)) {
                auth()->user()->update(['email' => $emailVecino]);
            }

            // Guardar adjuntos
            if ($request->hasFile('adjuntos')) {
                foreach ($request->file('adjuntos') as $file) {
                    $path = $file->store('adjuntos', 'private');
                    SolicitudAdjunto::create([
                        'solicitud_id' => $solicitud->id,
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'uploaded_by' => auth()->id(),
                    ]);
                }
            }

            // Si es solicitud de recinto (por código legacy o por requisito), crear reserva
            $crearReserva = (in_array($tipo->codigo, ['RECINTOS_MUNICIPALES', 'RECINTOS_DEPORTIVOS']) || in_array('recinto', $tipo->campos_requisitos))
                && isset($request->datos['recinto_id'], $request->datos['fecha_inicio'], $request->datos['fecha_fin'], $request->datos['hora_inicio'], $request->datos['hora_fin']);
            if ($crearReserva) {
                RecintoReserva::create([
                    'recinto_id' => $request->datos['recinto_id'],
                    'solicitud_id' => $solicitud->id,
                    'fecha_inicio' => $request->datos['fecha_inicio'],
                    'hora_inicio' => $request->datos['hora_inicio'],
                    'fecha_fin' => $request->datos['fecha_fin'],
                    'hora_fin' => $request->datos['hora_fin'],
                    'estado' => 'pendiente',
                ]);
            }

            // Crear evento inicial
            SolicitudEvento::create([
                'solicitud_id' => $solicitud->id,
                'actor_user_id' => auth()->id(),
                'evento' => 'solicitud_creada',
                'estado_nuevo' => 'enviada',
                'comentario' => 'Solicitud creada por el vecino',
            ]);

            DB::commit();

            $solicitud->refresh();
            SolicitudNotificacionService::enviarConfirmacionCreacionSeguro($solicitud);

            return response()->json([
                'success' => true,
                'folio' => $folio,
                'solicitud_id' => $solicitud->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function descargarAdjunto($solicitudId, $adjuntoId)
    {
        $solicitud = Solicitud::where('vecino_id', auth()->id())->findOrFail($solicitudId);
        $adjunto = SolicitudAdjunto::where('solicitud_id', $solicitud->id)->findOrFail($adjuntoId);

        if (!Storage::disk('private')->exists($adjunto->path)) {
            abort(404);
        }

        return Storage::disk('private')->download($adjunto->path, $adjunto->filename);
    }
}
