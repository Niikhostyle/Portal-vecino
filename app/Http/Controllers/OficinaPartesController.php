<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\SolicitudAdjunto;
use App\Models\SolicitudEvento;
use App\Models\User;
use App\Services\SolicitudNotificacionService;
use Illuminate\Support\Facades\DB;

class OficinaPartesController extends Controller
{
    public function bandeja(Request $request)
    {
        $query = Solicitud::with(['tipo', 'vecino', 'asignado'])
            ->whereIn('estado', ['enviada', 'en_revision_op']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('seccion')) {
            $query->whereHas('tipo', function($q) use ($request) {
                $q->where('seccion', $request->seccion);
            });
        }

        if ($request->filled('folio')) {
            $query->where('folio', 'like', '%' . $request->folio . '%');
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('op.bandeja', compact('solicitudes'));
    }

    public function showSolicitud($id)
    {
        $solicitud = Solicitud::with(['tipo', 'vecino', 'asignado', 'adjuntos', 'eventos.actor'])
            ->findOrFail($id);

        $funcionarios = User::where('rol', 'funcionario')
            ->where('estado', 'activo')
            ->orderBy('name')
            ->get();

        return view('op.solicitud-show', compact('solicitud', 'funcionarios'));
    }

    public function derivar(Request $request, $id)
    {
        $request->validate([
            'funcionario_id' => 'required|exists:users,id',
            'prioridad' => 'required|in:baja,normal,alta,urgente',
            'comentario' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $solicitud = Solicitud::findOrFail($id);
            
            $solicitud->update([
                'estado' => 'derivada',
                'asignado_user_id' => $request->funcionario_id,
                'prioridad' => $request->prioridad,
            ]);

            SolicitudEvento::create([
                'solicitud_id' => $solicitud->id,
                'actor_user_id' => auth()->id(),
                'evento' => 'solicitud_derivada',
                'estado_nuevo' => 'derivada',
                'comentario' => $request->comentario ?? 'Solicitud derivada a funcionario',
            ]);

            DB::commit();

            return redirect()->route('op.bandeja')
                ->with('success', 'Solicitud derivada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al derivar solicitud: ' . $e->getMessage());
        }
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $solicitud = Solicitud::findOrFail($id);
            
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

            return redirect()->route('op.bandeja')
                ->with('success', 'Solicitud rechazada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar solicitud: ' . $e->getMessage());
        }
    }

    public function responder(Request $request, $id)
    {
        $request->validate([
            'respuesta' => 'required|string',
            'adjuntos.*' => 'file|mimes:pdf,jpg,jpeg|max:5120',
        ]);

        $solicitud = Solicitud::findOrFail($id);
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
                'comentario' => 'Respuesta enviada al vecino desde OIRS',
            ]);

            DB::commit();

            $solicitud->refresh();
            SolicitudNotificacionService::enviarNotificacionRespuestaSeguro($solicitud);

            return redirect()->route('op.bandeja')
                ->with('success', 'Respuesta enviada exitosamente al vecino');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al enviar respuesta: ' . $e->getMessage());
        }
    }
}
