<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\SolicitudEvento;
use App\Models\SolicitudAdjunto;
use App\Services\SolicitudNotificacionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FuncionarioController extends Controller
{
    public function asignadas(Request $request)
    {
        $query = Solicitud::with(['tipo', 'vecino'])
            ->where('asignado_user_id', auth()->id());

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            $query->whereIn('estado', ['derivada', 'en_gestion']);
        }

        if ($request->filled('folio')) {
            $query->where('folio', 'like', '%' . $request->folio . '%');
        }

        $solicitudes = $query->orderBy('prioridad', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('funcionario.asignadas', compact('solicitudes'));
    }

    /**
     * Historial completo de solicitudes asignadas (auditoría interna)
     */
    public function historial(Request $request)
    {
        $query = Solicitud::with(['tipo', 'vecino'])
            ->where('asignado_user_id', auth()->id());

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('folio')) {
            $query->where('folio', 'like', '%' . $request->folio . '%');
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('funcionario.historial', compact('solicitudes'));
    }

    public function showSolicitud($id)
    {
        $solicitud = Solicitud::with(['tipo', 'vecino', 'adjuntos', 'eventos.actor'])
            ->where('asignado_user_id', auth()->id())
            ->findOrFail($id);

        return view('funcionario.solicitud-show', compact('solicitud'));
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|min:10|max:1000',
        ]);

        $solicitud = Solicitud::where('asignado_user_id', auth()->id())->findOrFail($id);
        if (in_array($solicitud->estado, ['respondida', 'rechazada'])) {
            return back()->with('error', 'No se puede modificar una solicitud ya respondida o rechazada.');
        }

        DB::beginTransaction();
        try {
            $solicitud->update([
                'estado' => 'rechazada',
                'motivo_rechazo' => $request->motivo,
            ]);

            SolicitudEvento::create([
                'solicitud_id' => $solicitud->id,
                'actor_user_id' => auth()->id(),
                'evento' => 'solicitud_rechazada',
                'estado_nuevo' => 'rechazada',
                'comentario' => $request->motivo,
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Solicitud rechazada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar solicitud: ' . $e->getMessage());
        }
    }

    public function responder(Request $request, $id)
    {
        $request->validate([
            'respuesta' => 'required|string|min:10',
            // Solo permitir PDF y JPG/JPEG
            'adjuntos.*' => 'file|mimes:pdf,jpg,jpeg|max:5120',
        ]);

        $solicitud = Solicitud::where('asignado_user_id', auth()->id())->findOrFail($id);
        if (in_array($solicitud->estado, ['respondida', 'rechazada'])) {
            return back()->with('error', 'No se puede modificar una solicitud ya respondida o rechazada.');
        }

        DB::beginTransaction();
        try {
            $solicitud->update([
                'estado' => 'respondida',
                'respuesta' => $request->respuesta,
                'fecha_respuesta' => now(),
            ]);

            // Guardar adjuntos de respuesta
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

            SolicitudEvento::create([
                'solicitud_id' => $solicitud->id,
                'actor_user_id' => auth()->id(),
                'evento' => 'solicitud_respondida',
                'estado_nuevo' => 'respondida',
                'comentario' => 'Respuesta enviada al vecino',
            ]);

            DB::commit();

            $solicitud->refresh();
            SolicitudNotificacionService::enviarNotificacionRespuestaSeguro($solicitud);

            return redirect()->route('dashboard')->with('success', 'Respuesta enviada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al enviar respuesta: ' . $e->getMessage());
        }
    }

    public function solicitarInfo(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string|min:10|max:1000',
        ]);

        $solicitud = Solicitud::where('asignado_user_id', auth()->id())->findOrFail($id);
        if (in_array($solicitud->estado, ['respondida', 'rechazada'])) {
            return back()->with('error', 'No se puede modificar una solicitud ya respondida o rechazada.');
        }

        DB::beginTransaction();
        try {
            SolicitudEvento::create([
                'solicitud_id' => $solicitud->id,
                'actor_user_id' => auth()->id(),
                'evento' => 'solicitud_info_adicional',
                'estado_nuevo' => $solicitud->estado,
                'comentario' => $request->comentario,
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Solicitud de información adicional enviada');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al solicitar información: ' . $e->getMessage());
        }
    }

    public function descargarAdjunto($solicitudId, $adjuntoId)
    {
        $solicitud = Solicitud::where('asignado_user_id', auth()->id())->findOrFail($solicitudId);
        $adjunto = SolicitudAdjunto::where('solicitud_id', $solicitud->id)->findOrFail($adjuntoId);

        if (!Storage::disk('private')->exists($adjunto->path)) {
            abort(404);
        }

        return Storage::disk('private')->download($adjunto->path, $adjunto->filename);
    }
}
