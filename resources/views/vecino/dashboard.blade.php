@extends('layouts.app')

@section('title', 'OIRS Digital')
@section('header_title', 'OIRS Digital')

@php
    $nombreCompleto = trim(auth()->user()->name ?? '');
    $partesNombre = preg_split('/\s+/', $nombreCompleto, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $cantidadPartes = count($partesNombre);
    if ($cantidadPartes >= 3) {
        // nombres + apellidos: primer nombre + primer apellido (antepenúltima palabra)
        $saludoNombre = $partesNombre[0] . ' ' . $partesNombre[$cantidadPartes - 2];
    } elseif ($cantidadPartes === 2) {
        $saludoNombre = $partesNombre[0] . ' ' . $partesNombre[1];
    } else {
        $saludoNombre = $partesNombre[0] ?? 'Vecino';
    }

    $categorias = [
        [
            'key' => 'felicitacion',
            'titulo' => 'Felicitaciones',
            'desc' => 'Reconoce un buen servicio o atención recibida.',
            'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            'c1' => '#fbbf24', 'c2' => '#f97316',
            'icobg' => '#fffbeb', 'icofg' => '#d97706',
            'ring' => 'hover:border-amber-300',
        ],
        [
            'key' => 'reclamo',
            'titulo' => 'Reclamos',
            'desc' => 'Reporta un problema o una mala experiencia.',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'c1' => '#fb7185', 'c2' => '#ef4444',
            'icobg' => '#fff1f2', 'icofg' => '#e11d48',
            'ring' => 'hover:border-rose-300',
        ],
        [
            'key' => 'informacion',
            'titulo' => 'Información',
            'desc' => 'Solicita información sobre trámites o servicios.',
            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'c1' => '#38bdf8', 'c2' => '#2563eb',
            'icobg' => '#f0f9ff', 'icofg' => '#0284c7',
            'ring' => 'hover:border-sky-300',
        ],
        [
            'key' => 'sugerencia',
            'titulo' => 'Sugerencias',
            'desc' => 'Propón una idea para mejorar la municipalidad.',
            'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
            'c1' => '#a78bfa', 'c2' => '#7c3aed',
            'icobg' => '#f5f3ff', 'icofg' => '#7c3aed',
            'ring' => 'hover:border-violet-300',
        ],
    ];

    $linkBase = $oirsTipoId
        ? route('vecino.iniciar-solicitud', $oirsTipoId)
        : route('vecino.solicitudes');
@endphp

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-6xl px-4 pt-6 pb-10 sm:px-6 lg:px-8">

        {{-- HERO / Bienvenida --}}
        <div class="relative overflow-hidden rounded-2xl px-6 py-8 shadow-lg sm:px-10 sm:py-10"
             style="background-image: linear-gradient(135deg, #1d4ed8 0%, #2563eb 45%, #4f46e5 100%);">
            <div class="pointer-events-none absolute -right-10 -top-10 h-44 w-44 rounded-full" style="background-color: rgba(255,255,255,.1);"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-10 h-52 w-52 rounded-full" style="background-color: rgba(255,255,255,.06);"></div>

            <div class="relative">
                <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider"
                     style="background-color: rgba(255,255,255,.18); color: #ffffff;">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 3v-3z"></path>
                    </svg>
                    OIRS Digital · Municipalidad de Chanco
                </div>

                <h1 class="mt-4 text-2xl font-bold sm:text-3xl" style="color: #ffffff;">
                    Bienvenido/a, {{ $saludoNombre }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed sm:text-base" style="color: #dbeafe;">
                    Oficina de Informaciones, Reclamos y Sugerencias. Aquí puedes enviar tu solicitud
                    y dar seguimiento a tus trámites con la municipalidad. Selecciona una categoría para comenzar.
                </p>
            </div>
        </div>

        {{-- CAJAS OIRS (izquierda) + RESUMEN VERTICAL (derecha) --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- 4 cajas OIRS en cuadrícula 2x2 --}}
            <div class="lg:col-span-2">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">¿Qué deseas realizar hoy?</h2>
                <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach($categorias as $cat)
                        <a href="{{ $linkBase }}?tipo_oirs={{ $cat['key'] }}"
                           class="group relative flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md {{ $cat['ring'] }}">
                            <div class="mb-1 h-1.5 w-12 rounded-full" style="background-image: linear-gradient(90deg, {{ $cat['c1'] }}, {{ $cat['c2'] }});"></div>
                            <div class="mt-3 inline-flex h-12 w-12 items-center justify-center rounded-xl"
                                 style="background-color: {{ $cat['icobg'] }}; color: {{ $cat['icofg'] }};">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat['icon'] }}"></path>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-bold text-slate-900">{{ $cat['titulo'] }}</h3>
                            <p class="mt-1 flex-1 text-sm leading-snug text-slate-500">{{ $cat['desc'] }}</p>
                            <span class="mt-4 inline-flex items-center text-sm font-semibold text-blue-600 group-hover:text-blue-700">
                                Comenzar
                                <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- KPIs en columna vertical (se estiran para igualar la altura de las cajas) --}}
            <div class="flex flex-col lg:col-span-1">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Mi resumen</h2>
                <div class="mt-3 flex flex-1 flex-col gap-4">
                    <div class="flex flex-1 items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Total</p>
                            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                        </div>
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: #f1f5f9; color: #475569;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </span>
                    </div>
                    <div class="flex flex-1 items-center justify-between rounded-2xl border border-amber-200 p-5 shadow-sm" style="background-color: #fffbeb;">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider" style="color: #92400e;">En trámite</p>
                            <p class="mt-1 text-3xl font-bold" style="color: #78350f;">{{ $stats['pendientes'] }}</p>
                        </div>
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: #fef3c7; color: #d97706;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                    </div>
                    <div class="flex flex-1 items-center justify-between rounded-2xl border border-emerald-200 p-5 shadow-sm" style="background-color: #ecfdf5;">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider" style="color: #065f46;">Resueltas</p>
                            <p class="mt-1 text-3xl font-bold" style="color: #064e3b;">{{ $stats['respondida'] }}</p>
                        </div>
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: #d1fae5; color: #059669;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRÁFICOS (mismo tamaño, alineados) --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-900">Mi actividad (6 meses)</p>
                <div class="mt-4 h-72 flex-1">
                    <canvas id="chartActividad"></canvas>
                </div>
            </div>
            <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-900">Por estado</p>
                <div class="mt-4 h-72 flex-1">
                    <canvas id="chartEstado"></canvas>
                </div>
            </div>
        </div>

        {{-- ÚLTIMAS SOLICITUDES --}}
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 sm:px-5">
                <p class="text-sm font-semibold text-slate-900">Mis últimas solicitudes</p>
                <a href="{{ route('vecino.mis-solicitudes') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                    Ver todas →
                </a>
            </div>

            @if($mis_solicitudes->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($mis_solicitudes as $solicitud)
                        @php
                            $estadoBadge = match($solicitud->estado) {
                                'respondida' => ['bg-emerald-100', 'text-emerald-800', 'Resuelta'],
                                'rechazada' => ['bg-rose-100', 'text-rose-800', 'Rechazada'],
                                default => ['bg-amber-100', 'text-amber-800', 'En trámite'],
                            };
                            $titulo = optional($solicitud->tipo)->codigo === 'OIRS'
                                ? 'OIRS · ' . \Illuminate\Support\Str::ucfirst($solicitud->datos_json['tipo_oirs'] ?? 'Solicitud')
                                : \Illuminate\Support\Str::limit(optional($solicitud->tipo)->titulo, 28);
                        @endphp
                        <div class="flex items-center gap-3 px-4 py-3 sm:px-5 hover:bg-slate-50/60">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-900">{{ $titulo }}</p>
                                <p class="text-xs text-slate-500">{{ $solicitud->folio }} · {{ $solicitud->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="hidden shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold sm:inline-flex {{ $estadoBadge[0] }} {{ $estadoBadge[1] }}">
                                {{ $estadoBadge[2] }}
                            </span>
                            <a href="{{ route('vecino.solicitud.show', $solicitud->id) }}"
                               class="shrink-0 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                Ver
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 py-10 text-center sm:px-5">
                    <p class="text-sm text-slate-600">Aún no tienes solicitudes. Elige una categoría arriba para comenzar.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(() => {
    const charts = @json($charts ?? []);

    const ctxAct = document.getElementById('chartActividad');
    if (ctxAct) {
        new Chart(ctxAct, {
            type: 'line',
            data: {
                labels: charts.labels || [],
                datasets: [{
                    label: 'Solicitudes',
                    data: charts.serie_total || [],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,.1)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const est = charts.por_estado || {};
    const ctxEst = document.getElementById('chartEstado');
    if (ctxEst) {
        new Chart(ctxEst, {
            type: 'doughnut',
            data: {
                labels: ['En trámite', 'Resueltas', 'Rechazadas'],
                datasets: [{
                    data: [est.pendientes || 0, est.respondidas || 0, est.rechazadas || 0],
                    backgroundColor: ['#f59e0b', '#10b981', '#f43f5e'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                cutout: '58%',
            }
        });
    }
})();
</script>
@endpush
@endsection
