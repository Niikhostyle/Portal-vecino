<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\SolicitudTipo;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdministrador()) {
            return $this->dashboardAdmin();
        } elseif ($user->isOficinaPartes()) {
            return $this->dashboardOP();
        } elseif ($user->isFuncionario()) {
            return $this->dashboardFuncionario();
        } else {
            return $this->dashboardVecino();
        }
    }

    private function dashboardAdmin()
    {
        $stats = [
            'total_solicitudes' => Solicitud::count(),
            'enviadas' => Solicitud::where('estado', 'enviada')->count(),
            'en_revision_op' => Solicitud::where('estado', 'en_revision_op')->count(),
            'derivadas' => Solicitud::where('estado', 'derivada')->count(),
            'en_gestion' => Solicitud::where('estado', 'en_gestion')->count(),
            'respondidas' => Solicitud::where('estado', 'respondida')->count(),
            'rechazadas' => Solicitud::where('estado', 'rechazada')->count(),
            'pendientes_op' => Solicitud::whereIn('estado', ['enviada', 'en_revision_op'])->count(),
        ];

        $solicitudes_recientes = Solicitud::with(['tipo', 'vecino', 'asignado'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'solicitudes_recientes'));
    }

    private function dashboardOP()
    {
        $stats = [
            'total_solicitudes' => Solicitud::count(),
            'pendientes_op' => Solicitud::whereIn('estado', ['enviada', 'en_revision_op'])->count(),
            'respondidas' => Solicitud::where('estado', 'respondida')->count(),
            'rechazadas' => Solicitud::where('estado', 'rechazada')->count(),
        ];

        $nuevas_solicitudes = Solicitud::with(['tipo', 'vecino'])
            ->whereIn('estado', ['enviada', 'en_revision_op'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('op.dashboard', compact('nuevas_solicitudes', 'stats'));
    }

    private function dashboardFuncionario()
    {
        $baseQuery = Solicitud::where('asignado_user_id', auth()->id());

        $stats = [
            'total_asignadas' => (clone $baseQuery)->count(),
            'pendientes' => (clone $baseQuery)->whereIn('estado', ['derivada', 'en_gestion'])->count(),
            'respondidas' => (clone $baseQuery)->where('estado', 'respondida')->count(),
            'en_gestion' => (clone $baseQuery)->where('estado', 'en_gestion')->count(),
            'derivadas' => (clone $baseQuery)->where('estado', 'derivada')->count(),
        ];

        $pendientes = Solicitud::with(['tipo', 'vecino'])
            ->where('asignado_user_id', auth()->id())
            ->whereIn('estado', ['derivada', 'en_gestion'])
            ->orderBy('prioridad', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        $solicitudes_respondidas = Solicitud::with(['tipo', 'vecino'])
            ->where('asignado_user_id', auth()->id())
            ->where('estado', 'respondida')
            ->orderBy('fecha_respuesta', 'desc')
            ->limit(10)
            ->get();

        $historial_reciente = Solicitud::with(['tipo', 'vecino'])
            ->where('asignado_user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        return view('funcionario.dashboard', compact(
            'stats', 'pendientes', 'solicitudes_respondidas', 'historial_reciente'
        ));
    }

    private function dashboardVecino()
    {
        $userId = auth()->id();
        $baseQuery = Solicitud::where('vecino_id', $userId);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'enviada' => (clone $baseQuery)->where('estado', 'enviada')->count(),
            'en_revision_op' => (clone $baseQuery)->where('estado', 'en_revision_op')->count(),
            'derivada' => (clone $baseQuery)->where('estado', 'derivada')->count(),
            'en_gestion' => (clone $baseQuery)->where('estado', 'en_gestion')->count(),
            'respondida' => (clone $baseQuery)->where('estado', 'respondida')->count(),
            'rechazada' => (clone $baseQuery)->where('estado', 'rechazada')->count(),
        ];
        $stats['pendientes'] = $stats['enviada'] + $stats['en_revision_op'] + $stats['derivada'] + $stats['en_gestion'];

        // Últimos 6 meses para el gráfico
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $meses[] = [
                'label' => $fecha->translatedFormat('M y'),
                'total' => (clone $baseQuery)->whereYear('created_at', $fecha->year)->whereMonth('created_at', $fecha->month)->count(),
            ];
        }

        $mis_solicitudes = Solicitud::with(['tipo', 'asignado'])
            ->where('vecino_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('vecino.dashboard', compact('stats', 'meses', 'mis_solicitudes'));
    }
}
