<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\SolicitudTipo;
use App\Models\User;
use App\Services\ChancoNoticiasService;
use Illuminate\Support\Facades\DB;

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

        [$oirsStats, $oirsCharts, $oirsUltimas] = $this->buildOirsDashboardData(false);

        return view('admin.dashboard', compact('stats', 'solicitudes_recientes', 'oirsStats', 'oirsCharts', 'oirsUltimas'));
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

        [$oirsStats, $oirsCharts, $oirsUltimas] = $this->buildOirsDashboardData(true);

        return view('op.dashboard', compact('nuevas_solicitudes', 'stats', 'oirsStats', 'oirsCharts', 'oirsUltimas'));
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

        $labels = [];
        $serieTotal = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $labels[] = $fecha->translatedFormat('M');
            $serieTotal[] = (clone $baseQuery)
                ->whereYear('created_at', $fecha->year)
                ->whereMonth('created_at', $fecha->month)
                ->count();
        }

        $charts = [
            'labels' => $labels,
            'serie_total' => $serieTotal,
            'por_estado' => [
                'pendientes' => $stats['pendientes'],
                'respondidas' => $stats['respondida'],
                'rechazadas' => $stats['rechazada'],
            ],
            'por_tipo' => Solicitud::query()
                ->where('vecino_id', $userId)
                ->join('solicitud_tipos', 'solicitudes.tipo_id', '=', 'solicitud_tipos.id')
                ->select('solicitud_tipos.titulo', DB::raw('COUNT(*) as total'))
                ->groupBy('solicitud_tipos.titulo')
                ->orderByDesc('total')
                ->limit(5)
                ->pluck('total', 'titulo')
                ->all(),
        ];

        $mis_solicitudes = Solicitud::with(['tipo', 'asignado'])
            ->where('vecino_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Tipo OIRS para enlazar las cajas de bienvenida directamente al formulario
        $oirsTipo = SolicitudTipo::where('codigo', 'OIRS')->first();
        $oirsTipoId = $oirsTipo?->id;

        $noticias = app(ChancoNoticiasService::class)->destacadas(3);
        $noticiasVerTodasUrl = app(ChancoNoticiasService::class)->verTodasUrl();

        return view('vecino.dashboard', compact('stats', 'charts', 'mis_solicitudes', 'oirsTipoId', 'noticias', 'noticiasVerTodasUrl'));
    }

    /**
     * Arma datos del dashboard estilo OIRS Digital.
     *
     * @return array{0: array<string,mixed>, 1: array<string,mixed>, 2: \Illuminate\Support\Collection}
     */
    private function buildOirsDashboardData(bool $soloPendientes = false): array
    {
        $oirsTipoId = SolicitudTipo::where('codigo', 'OIRS')->value('id');

        $base = Solicitud::query()
            ->when($oirsTipoId, fn ($q) => $q->where('tipo_id', $oirsTipoId));

        if ($soloPendientes) {
            $base = $base->whereIn('estado', ['enviada', 'en_revision_op', 'derivada', 'en_gestion']);
        }

        $total = (clone $base)->count();
        $pendientes = (clone $base)->whereIn('estado', ['enviada', 'en_revision_op', 'derivada', 'en_gestion'])->count();
        $resueltas = (clone $base)->whereIn('estado', ['respondida', 'rechazada'])->count();

        $avgDays = (clone $base)
            ->whereNotNull('fecha_respuesta')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, fecha_respuesta)) as avg_hours'))
            ->value('avg_hours');
        $avgDays = $avgDays ? round(((float) $avgDays) / 24, 1) : null;

        // Últimos 6 meses: total y por tipo_oirs (informacion/reclamo/sugerencia)
        $labels = [];
        $serieTotal = [];
        $serieInfo = [];
        $serieReclamo = [];
        $serieSugerencia = [];

        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $labels[] = $fecha->translatedFormat('M');

            $mesBase = (clone $base)->whereYear('created_at', $fecha->year)->whereMonth('created_at', $fecha->month);
            $serieTotal[] = (clone $mesBase)->count();

            $serieInfo[] = (clone $mesBase)->where('datos_json->tipo_oirs', 'informacion')->count();
            $serieReclamo[] = (clone $mesBase)->where('datos_json->tipo_oirs', 'reclamo')->count();
            $serieSugerencia[] = (clone $mesBase)->where('datos_json->tipo_oirs', 'sugerencia')->count();
        }

        $porTipo = (clone $base)
            ->selectRaw("COALESCE(JSON_UNQUOTE(JSON_EXTRACT(datos_json, '$.tipo_oirs')), 'otros') as tipo_oirs, COUNT(*) as total")
            ->groupBy('tipo_oirs')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn ($row) => [(string) $row->tipo_oirs => (int) $row->total])
            ->all();

        $porEstado = (clone $base)
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn ($row) => [(string) $row->estado => (int) $row->total])
            ->all();

        $oirsStats = [
            'total' => $total,
            'pendientes' => $pendientes,
            'resueltas' => $resueltas,
            'tiempo_promedio_dias' => $avgDays,
        ];

        $oirsCharts = [
            'labels' => $labels,
            'serie_total' => $serieTotal,
            'serie_informacion' => $serieInfo,
            'serie_reclamo' => $serieReclamo,
            'serie_sugerencia' => $serieSugerencia,
            'por_tipo' => $porTipo,
            'por_estado' => $porEstado,
        ];

        $oirsUltimas = (clone $base)
            ->with(['vecino', 'tipo'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return [$oirsStats, $oirsCharts, $oirsUltimas];
    }
}
