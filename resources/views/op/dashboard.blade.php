@extends('layouts.app')

@section('title', 'Dashboard Oficina de Partes')
@section('header_title', 'Panel de control')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto max-w-7xl px-4 pt-6 pb-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">OIRS Digital · Municipalidad</h1>
                <p class="mt-1 text-sm text-slate-600">Panel de gestión · Resumen</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Sistema en línea
                </span>
                <span class="hidden sm:inline">{{ now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}</span>
            </div>
        </div>

        <!-- Resumen estilo OIRS -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Solicitudes OIRS</p>
                <div class="mt-2 flex items-end justify-between">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($oirsStats['total'] ?? 0) }}</p>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">Total</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pendientes</p>
                <div class="mt-2 flex items-end justify-between">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($oirsStats['pendientes'] ?? 0) }}</p>
                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Pendientes</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Resueltas</p>
                <div class="mt-2 flex items-end justify-between">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($oirsStats['resueltas'] ?? 0) }}</p>
                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Resueltas</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tiempo Promedio</p>
                <div class="mt-2 flex items-end justify-between">
                    <p class="text-3xl font-bold text-slate-900">
                        {{ $oirsStats['tiempo_promedio_dias'] !== null ? $oirsStats['tiempo_promedio_dias'] : '—' }}
                    </p>
                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">días</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <!-- Tendencia mensual -->
            <div class="lg:col-span-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Tendencia Mensual de Solicitudes</h2>
                    <p class="mt-1 text-sm text-slate-600">Últimos 6 meses (OIRS)</p>
                </div>
                <div class="px-6 py-4">
                    <div class="h-72">
                        <canvas id="chartTendencia"></canvas>
                    </div>
                </div>
            </div>

            <!-- Donut por tipo -->
            <div class="lg:col-span-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Solicitudes por Tipo</h2>
                    <p class="mt-1 text-sm text-slate-600">Distribución OIRS</p>
                </div>
                <div class="px-6 py-4">
                    <div class="h-64">
                        <canvas id="chartTipos"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabla últimas solicitudes OIRS -->
            <div class="lg:col-span-12 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Últimas Solicitudes Registradas</h2>
                        <p class="mt-1 text-sm text-slate-600">OIRS recientes</p>
                    </div>
                    <a href="{{ route('op.bandeja') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Ver bandeja
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($oirsUltimas ?? collect() as $solicitud)
                                @php
                                    $tipoOirs = data_get($solicitud->datos_json, 'tipo_oirs', 'otros');
                                    $tipoLabel = match($tipoOirs) {
                                        'felicitacion' => 'Felicitación',
                                        'informacion' => 'Información',
                                        'reclamo' => 'Reclamo',
                                        'sugerencia' => 'Sugerencia',
                                        default => ucfirst((string)$tipoOirs),
                                    };
                                @endphp
                                <tr class="transition-colors hover:bg-slate-50/50">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $solicitud->folio }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $tipoLabel }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $solicitud->created_at?->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-900">{{ $solicitud->vecino?->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badge = match($solicitud->estado) {
                                                'respondida' => ['bg' => 'bg-emerald-100', 'tx' => 'text-emerald-800', 'lb' => 'Cerrada'],
                                                'rechazada' => ['bg' => 'bg-rose-100', 'tx' => 'text-rose-800', 'lb' => 'Rechazada'],
                                                'enviada', 'en_revision_op' => ['bg' => 'bg-amber-100', 'tx' => 'text-amber-800', 'lb' => 'Pendiente'],
                                                'derivada', 'en_gestion' => ['bg' => 'bg-blue-100', 'tx' => 'text-blue-800', 'lb' => 'En Proceso'],
                                                default => ['bg' => 'bg-slate-100', 'tx' => 'text-slate-800', 'lb' => ucfirst(str_replace('_',' ', $solicitud->estado))],
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full {{ $badge['bg'] }} px-2.5 py-0.5 text-xs font-semibold {{ $badge['tx'] }}">
                                            {{ $badge['lb'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('op.solicitud.show', $solicitud->id) }}"
                                           class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-600">Aún no hay solicitudes OIRS.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(() => {
    const charts = @json($oirsCharts ?? []);

    const labels = charts.labels || [];
    const ctxLine = document.getElementById('chartTendencia');
    if (ctxLine) {
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Total', data: charts.serie_total || [], borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,.12)', tension: 0.35, fill: true, pointRadius: 3 },
                    { label: 'Información', data: charts.serie_informacion || [], borderColor: '#10b981', tension: 0.35, fill: false, pointRadius: 2 },
                    { label: 'Reclamo', data: charts.serie_reclamo || [], borderColor: '#f97316', tension: 0.35, fill: false, pointRadius: 2 },
                    { label: 'Sugerencia', data: charts.serie_sugerencia || [], borderColor: '#8b5cf6', tension: 0.35, fill: false, pointRadius: 2 },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const tipoMap = charts.por_tipo || {};
    const tipoLabels = Object.keys(tipoMap).map(k => ({
        felicitacion: 'Felicitación',
        informacion: 'Información',
        reclamo: 'Reclamo',
        sugerencia: 'Sugerencia',
        otros: 'Otros',
    }[k] || (k.charAt(0).toUpperCase() + k.slice(1))));
    const tipoData = Object.values(tipoMap);

    const ctxDonut = document.getElementById('chartTipos');
    if (ctxDonut) {
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: tipoLabels,
                datasets: [{
                    data: tipoData,
                    backgroundColor: ['#0ea5e9', '#10b981', '#f97316', '#94a3b8', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                cutout: '62%'
            }
        });
    }
})();
</script>
@endpush
@endsection
