<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\SolicitudTipo;
use App\Models\SolicitudAdjunto;
use App\Models\SolicitudEvento;
use App\Models\RecintoReserva;
use App\Models\Recinto;
use App\Models\User;
use App\Services\SolicitudNotificacionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * Permite a funcionarios, oficina de partes y administradores
 * crear solicitudes en nombre de ciudadanos.
 */
class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $tipos_solicitud = SolicitudTipo::habilitados()
            ->orderBy('seccion')
            ->orderBy('titulo')
            ->get()
            ->groupBy('seccion');

        $vecinos = User::where('rol', 'vecino')
            ->where('estado', 'activo')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $vecinoPreseleccionado = $request->query('vecino_id');

        return view('staff.crear-solicitud.index', compact('tipos_solicitud', 'vecinos', 'vecinoPreseleccionado'));
    }

    /**
     * Formulario para registrar un ciudadano manualmente (sin Clave Única).
     */
    public function crearVecino()
    {
        return view('staff.vecinos.create');
    }

    /**
     * Guardar ciudadano registrado manualmente.
     */
    public function storeVecino(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'rut' => 'required|string|max:12',
        ]);

        $rut = preg_replace('/[.\s]/', '', trim($request->rut));
        $partes = explode('-', $rut, 2);
        $run = null;
        $dv = null;
        if (count($partes) === 2) {
            $run = preg_replace('/\D/', '', $partes[0]);
            $dv = strtoupper(trim($partes[1]));
        } elseif (preg_match('/^(\d{1,8})([0-9kK])$/', $rut, $m)) {
            $run = $m[1];
            $dv = strtoupper($m[2]);
        }
        if (! $run || ! $dv || ! preg_match('/^[0-9kK]$/', $dv)) {
            return back()->withInput()->withErrors(['rut' => 'El RUT ingresado no es válido. Use formato 12.345.678-9']);
        }
        if (strlen($run) > 8) {
            return back()->withInput()->withErrors(['rut' => 'El RUT ingresado no es válido.']);
        }

        $existe = User::where('rol', 'vecino')
            ->where('run', $run)
            ->where('dv', $dv)
            ->exists();
        if ($existe) {
            return back()->withInput()->withErrors(['rut' => 'Ya existe un ciudadano registrado con este RUT.']);
        }

        if ($request->filled('email')) {
            $existeEmail = User::where('email', $request->email)->exists();
            if ($existeEmail) {
                return back()->withInput()->withErrors(['email' => 'El correo ya está registrado.']);
            }
        }

        $claveunicaId = $run . $dv;
        $vecino = User::create([
            'name' => $request->name,
            'email' => $request->email ?: null,
            'password' => null,
            'rol' => 'vecino',
            'estado' => 'activo',
            'run' => $run,
            'dv' => $dv,
            'claveunica_id' => $claveunicaId,
        ]);

        return redirect()->route('staff.crear-solicitud', ['vecino_id' => $vecino->id])
            ->with('success', 'Ciudadano registrado correctamente. Ya puede crear solicitudes en su nombre.');
    }

    public function iniciarSolicitud(Request $request, $tipoId)
    {
        $request->validate(['vecino_id' => 'required|exists:users,id']);
        $vecino = User::findOrFail($request->vecino_id);
        if ($vecino->rol !== 'vecino') {
            abort(403, 'Solo se pueden crear solicitudes para ciudadanos.');
        }

        $tipo = SolicitudTipo::findHabilitadoOrFail($tipoId);

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

        $ciudadano = $vecino;
        $datosPrecargados = $vecino->datosPrecargadosSolicitud();

        return view('vecino.solicitudes.wizard', compact('tipo', 'recintos', 'datosPrecargados', 'vecino', 'ciudadano'));
    }

    public function storeSolicitud(Request $request, $tipoId)
    {
        $tipo = SolicitudTipo::findHabilitadoOrFail($tipoId);

        $request->validate(
            array_merge([
                'vecino_id' => 'required|exists:users,id',
                'datos' => 'required|array',
                // Solo permitir PDF y JPG/JPEG
                'adjuntos.*' => 'file|mimes:pdf,jpg,jpeg|max:5120',
            ], $tipo->reglasValidacionStore()),
            $tipo->mensajesValidacionStore()
        );

        $vecino = User::findOrFail($request->vecino_id);
        if ($vecino->rol !== 'vecino') {
            return response()->json(['success' => false, 'message' => 'Solo se pueden crear solicitudes para ciudadanos.'], 403);
        }

        DB::beginTransaction();
        try {
            $year = date('Y');
            $ultimo = Solicitud::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $numero = $ultimo ? (int) substr($ultimo->folio, -6) + 1 : 1;
            $folio = 'CHANCO-' . $year . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);

            // Nombre y RUT provienen exclusivamente de Clave Única del ciudadano
            $datos = $vecino->aplicarIdentidadEnDatos($request->datos);

            if (empty($datos['rut'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ciudadano no tiene RUT registrado desde Clave Única. Debe ingresar al portal con ClaveÚnica antes de crear solicitudes.',
                ], 422);
            }

            $solicitud = Solicitud::create([
                'folio' => $folio,
                'tipo_id' => $tipoId,
                'vecino_id' => $vecino->id,
                'estado' => 'enviada',
                'datos_json' => $datos,
            ]);

            // Actualizar solo email del vecino (el RUT no se modifica; viene de Clave Única)
            $emailVecino = $request->datos['email'] ?? $request->datos['mail'] ?? null;
            if (! empty($emailVecino) && filter_var($emailVecino, FILTER_VALIDATE_EMAIL)) {
                $vecino->update(['email' => $emailVecino]);
            }

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

            SolicitudEvento::create([
                'solicitud_id' => $solicitud->id,
                'actor_user_id' => auth()->id(),
                'evento' => 'solicitud_creada',
                'estado_nuevo' => 'enviada',
                'comentario' => 'Solicitud creada por ' . auth()->user()->name . ' en nombre del ciudadano',
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
}
