<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recinto;
use App\Models\RecintoReserva;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecintosController extends Controller
{
    public function calendario(Request $request)
    {
        $recintos = Recinto::where('activo', true)->get();
        $recintoSeleccionado = $request->get('recinto_id', $recintos->first()?->id);

        $fecha = $request->get('fecha', now()->format('Y-m'));
        $carbon = Carbon::createFromFormat('Y-m', $fecha);

        // Obtener reservas del mes
        $reservas = RecintoReserva::where('recinto_id', $recintoSeleccionado)
            ->whereYear('fecha_inicio', $carbon->year)
            ->whereMonth('fecha_inicio', $carbon->month)
            ->get()
            ->groupBy(function($reserva) {
                return Carbon::parse($reserva->fecha_inicio)->format('Y-m-d');
            });

        return view('recintos.calendario', compact('recintos', 'recintoSeleccionado', 'fecha', 'reservas', 'carbon'));
    }

    /**
     * Obtiene los horarios disponibles para un recinto en una fecha.
     * Slots de 1 hora: 08:00 a 21:00.
     */
    public function horariosDisponibles(Request $request, $recintoId)
    {
        $request->validate([
            'fecha' => 'required|date',
        ]);

        $fecha = $request->fecha;
        $slots = [];
        for ($h = 8; $h <= 21; $h++) {
            $slots[] = sprintf('%02d:00', $h);
        }

        $ocupados = RecintoReserva::where('recinto_id', $recintoId)
            ->where('estado', '!=', 'rechazada')
            ->whereDate('fecha_inicio', '<=', $fecha)
            ->whereDate('fecha_fin', '>=', $fecha)
            ->get();

        $disponibles = [];
        foreach ($slots as $slot) {
            $slotEnd = sprintf('%02d:00', (int)substr($slot, 0, 2) + 1);
            if ($slotEnd === '22:00') {
                $slotEnd = '22:00';
            }

            $ocupado = $ocupados->contains(function ($reserva) use ($fecha, $slot, $slotEnd) {
                $rInicio = $reserva->fecha_inicio->format('Y-m-d') . ' ' . $reserva->hora_inicio;
                $rFin = $reserva->fecha_fin->format('Y-m-d') . ' ' . $reserva->hora_fin;
                $sInicio = $fecha . ' ' . $slot;
                $sFin = $fecha . ' ' . $slotEnd;
                return $sInicio < $rFin && $sFin > $rInicio;
            });

            if (!$ocupado) {
                $disponibles[] = $slot;
            }
        }

        return response()->json([
            'disponibles' => $disponibles,
            'slots_totales' => $slots,
        ]);
    }

    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'recinto_id' => 'required|exists:recintos,id',
            'fecha_inicio' => 'required|date',
            'hora_inicio' => 'required',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'hora_fin' => 'required',
        ]);

        $nuevoInicio = Carbon::parse($request->fecha_inicio . ' ' . $request->hora_inicio);
        $nuevoFin = Carbon::parse($request->fecha_fin . ' ' . $request->hora_fin);

        $existeChoque = RecintoReserva::where('recinto_id', $request->recinto_id)
            ->where('estado', '!=', 'rechazada')
            ->get()
            ->contains(function ($reserva) use ($nuevoInicio, $nuevoFin) {
                $rInicio = Carbon::parse($reserva->fecha_inicio->format('Y-m-d') . ' ' . $reserva->hora_inicio);
                $rFin = Carbon::parse($reserva->fecha_fin->format('Y-m-d') . ' ' . $reserva->hora_fin);
                return $nuevoInicio->lt($rFin) && $nuevoFin->gt($rInicio);
            });

        return response()->json([
            'disponible' => !$existeChoque,
        ]);
    }

    public function reservas(Request $request)
    {
        $query = RecintoReserva::with(['recinto', 'solicitud.vecino']);

        if ($request->filled('recinto_id')) {
            $query->where('recinto_id', $request->recinto_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->orderBy('fecha_inicio', 'desc')->paginate(20);

        $recintos = Recinto::where('activo', true)->get();

        return view('recintos.reservas', compact('reservas', 'recintos'));
    }

    public function aprobarReserva($id)
    {
        DB::beginTransaction();
        try {
            $reserva = RecintoReserva::findOrFail($id);
            $reserva->update(['estado' => 'aprobada']);

            if ($reserva->solicitud) {
                $reserva->solicitud->update(['estado' => 'en_gestion']);
            }

            DB::commit();
            return back()->with('success', 'Reserva aprobada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al aprobar reserva');
        }
    }

    public function rechazarReserva(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            $reserva = RecintoReserva::findOrFail($id);
            $reserva->update(['estado' => 'rechazada']);

            if ($reserva->solicitud) {
                $reserva->solicitud->update([
                    'estado' => 'rechazada',
                    'motivo_rechazo' => $request->motivo,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Reserva rechazada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar reserva');
        }
    }
}
