<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\SolicitudEvento;
use App\Models\User;
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
            'motivo' => 'required|string|min:10|max:1000',
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
}
