@extends('layouts.app')

@section('title', 'OIRS Digital')
@section('header_title', 'Inicio')

@php
    $nombreCompleto = trim(auth()->user()->name ?? '');
    $partesNombre = preg_split('/\s+/', $nombreCompleto, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $cantidadPartes = count($partesNombre);
    if ($cantidadPartes >= 3) {
        $saludoNombre = $partesNombre[0] . ' ' . $partesNombre[$cantidadPartes - 2];
    } elseif ($cantidadPartes === 2) {
        $saludoNombre = $partesNombre[0] . ' ' . $partesNombre[1];
    } else {
        $saludoNombre = $partesNombre[0] ?? 'vecino';
    }

    $linkBase = $oirsTipoId
        ? route('vecino.iniciar-solicitud', $oirsTipoId)
        : route('vecino.solicitudes');

    $acciones = [
        [
            'key' => 'informacion',
            'titulo' => 'Realizar una Consulta',
            'desc' => 'Solicite información sobre trámites, servicios municipales o procedimientos administrativos.',
            'href' => $linkBase . '?tipo_oirs=informacion',
            'icobg' => '#dbeafe', 'icofg' => '#1d4ed8',
            'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        ],
        [
            'key' => 'reclamo',
            'titulo' => 'Presentar un Reclamo',
            'desc' => 'Informe sobre problemas con servicios municipales, atención deficiente o incumplimientos.',
            'href' => $linkBase . '?tipo_oirs=reclamo',
            'icobg' => '#ffedd5', 'icofg' => '#c2410c',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        ],
        [
            'key' => 'sugerencia',
            'titulo' => 'Enviar una Sugerencia',
            'desc' => 'Comparta ideas y propuestas para mejorar los servicios y la gestión municipal.',
            'href' => $linkBase . '?tipo_oirs=sugerencia',
            'icobg' => '#dcfce7', 'icofg' => '#15803d',
            'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
        ],
        [
            'key' => 'felicitacion',
            'titulo' => 'Enviar una Felicitación',
            'desc' => 'Reconozca el buen servicio o la atención recibida por funcionarios municipales.',
            'href' => $linkBase . '?tipo_oirs=felicitacion',
            'icobg' => '#ede9fe', 'icofg' => '#6d28d9',
            'icon' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5',
        ],
        [
            'key' => 'seguimiento',
            'titulo' => 'Seguimiento de Solicitudes',
            'desc' => 'Consulte el estado y avance de sus solicitudes ingresadas anteriormente.',
            'href' => route('vecino.mis-solicitudes'),
            'icobg' => '#ccfbf1', 'icofg' => '#0f766e',
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        ],
    ];
@endphp

@section('content')
<div class="min-h-screen" style="background-color: #ffffff;">

    {{-- HERO --}}
    <section class="border-b" style="background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%); border-color: #e2e8f0;">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider" style="color: #1d4ed8;">OIRS Digital · Municipalidad de Chanco</p>
                    <h1 class="mt-3 text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl" style="color: #0f2d5c;">
                        ¡Bienvenido/a, {{ $saludoNombre }}!
                    </h1>
                    <p class="mt-4 max-w-xl text-base leading-relaxed sm:text-lg" style="color: #475569;">
                        Este es el portal OIRS de la Municipalidad de Chanco. Aquí puede realizar consultas,
                        presentar reclamos, enviar sugerencias y felicitaciones de manera rápida y segura.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('vecino.solicitudes') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-opacity hover:opacity-90"
                           style="background-color: #0f2d5c;">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full" style="background-color: rgba(255,255,255,.2);">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </span>
                            Realizar Solicitud
                        </a>
                        <a href="{{ route('vecino.mis-solicitudes') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl border px-6 py-3.5 text-sm font-semibold transition-colors hover:bg-white"
                           style="border-color: #0f2d5c; color: #0f2d5c; background-color: rgba(255,255,255,.6);">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Ver Mis Solicitudes
                        </a>
                    </div>

                    <div class="mt-6 flex items-center gap-2 text-sm" style="color: #64748b;">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Horario de atención: Lunes a Viernes de 08:30 a 14:00 horas
                    </div>

                    @if(($stats['total'] ?? 0) > 0)
                        <div class="mt-6 flex flex-wrap gap-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" style="background-color: #e0e7ff; color: #3730a3;">
                                {{ $stats['total'] }} solicitud{{ $stats['total'] !== 1 ? 'es' : '' }}
                            </span>
                            @if(($stats['pendientes'] ?? 0) > 0)
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" style="background-color: #fef3c7; color: #92400e;">
                                    {{ $stats['pendientes'] }} en trámite
                                </span>
                            @endif
                            @if(($stats['respondida'] ?? 0) > 0)
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" style="background-color: #d1fae5; color: #065f46;">
                                    {{ $stats['respondida'] }} resueltas
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Ilustración municipal --}}
                <div class="hidden justify-center lg:flex">
                    <svg viewBox="0 0 480 320" class="w-full max-w-md" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect width="480" height="320" rx="16" fill="#e8f2ff"/>
                        <ellipse cx="240" cy="290" rx="200" ry="18" fill="#c7dcf5" opacity=".5"/>
                        <path d="M60 250 L60 140 L120 110 L180 140 L180 250 Z" fill="#1e3a5f"/>
                        <path d="M90 250 L90 160 L150 135 L150 250 Z" fill="#2d5a8e"/>
                        <rect x="108" y="175" width="24" height="30" rx="2" fill="#fbbf24"/>
                        <rect x="108" y="215" width="24" height="35" rx="2" fill="#fde68a"/>
                        <path d="M120 110 L120 85 L135 78 L150 85 L150 110 Z" fill="#dc2626"/>
                        <rect x="200" y="200" width="80" height="50" rx="4" fill="#1e3a5f"/>
                        <rect x="215" y="215" width="20" height="35" rx="2" fill="#93c5fd"/>
                        <rect x="245" y="215" width="20" height="35" rx="2" fill="#93c5fd"/>
                        <path d="M200 200 L240 165 L280 200 Z" fill="#2d5a8e"/>
                        <rect x="300" y="170" width="100" height="80" rx="4" fill="#3b6ea5"/>
                        <rect x="315" y="190" width="18" height="22" rx="2" fill="#bfdbfe"/>
                        <rect x="345" y="190" width="18" height="22" rx="2" fill="#bfdbfe"/>
                        <rect x="375" y="190" width="18" height="22" rx="2" fill="#bfdbfe"/>
                        <rect x="315" y="220" width="18" height="22" rx="2" fill="#bfdbfe"/>
                        <rect x="345" y="220" width="18" height="22" rx="2" fill="#bfdbfe"/>
                        <path d="M300 170 L350 130 L400 170 Z" fill="#1e3a5f"/>
                        <circle cx="380" cy="95" r="28" fill="#fbbf24" opacity=".9"/>
                        <path d="M30 250 Q80 230 130 250 T230 250 T330 250 T430 250 L480 250 L480 320 L0 320 Z" fill="#86efac" opacity=".4"/>
                        <ellipse cx="70" cy="240" rx="22" ry="30" fill="#22c55e" opacity=".7"/>
                        <ellipse cx="410" cy="235" rx="18" ry="28" fill="#22c55e" opacity=".7"/>
                        <rect x="155" y="238" width="50" height="6" rx="3" fill="#78716c"/>
                        <rect x="148" y="244" width="8" height="6" fill="#78716c"/>
                        <rect x="204" y="244" width="8" height="6" fill="#78716c"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- ACCIONES --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h2 class="text-center text-2xl font-bold sm:text-3xl" style="color: #0f2d5c;">¿Qué necesitas hacer?</h2>

        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach($acciones as $accion)
                <a href="{{ $accion['href'] }}"
                   class="group flex flex-col rounded-2xl border bg-white p-5 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md"
                   style="border-color: #e2e8f0;">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full"
                          style="background-color: {{ $accion['icobg'] }}; color: {{ $accion['icofg'] }};">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $accion['icon'] }}"></path>
                        </svg>
                    </span>
                    <h3 class="mt-4 text-base font-bold leading-snug" style="color: #0f2d5c;">{{ $accion['titulo'] }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed" style="color: #64748b;">{{ $accion['desc'] }}</p>
                    <span class="mt-4 inline-flex items-center text-sm font-semibold transition-transform group-hover:translate-x-1" style="color: #1d4ed8;">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ÚLTIMAS SOLICITUDES (compacto) --}}
    @if($mis_solicitudes->count() > 0)
        <section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border shadow-sm" style="border-color: #e2e8f0; background-color: #fff;">
                <div class="flex items-center justify-between border-b px-5 py-4" style="border-color: #e2e8f0; background-color: #f8fafc;">
                    <h3 class="text-sm font-semibold" style="color: #0f2d5c;">Mis solicitudes recientes</h3>
                    <a href="{{ route('vecino.mis-solicitudes') }}" class="text-xs font-semibold" style="color: #1d4ed8;">Ver todas →</a>
                </div>
                <div class="divide-y" style="border-color: #f1f5f9;">
                    @foreach($mis_solicitudes->take(3) as $solicitud)
                        @php
                            $estadoLabel = match($solicitud->estado) {
                                'respondida' => ['Resuelta', '#d1fae5', '#065f46'],
                                'rechazada' => ['Rechazada', '#ffe4e6', '#9f1239'],
                                default => ['En trámite', '#fef3c7', '#92400e'],
                            };
                            $titulo = optional($solicitud->tipo)->codigo === 'OIRS'
                                ? 'OIRS · ' . \Illuminate\Support\Str::ucfirst($solicitud->datos_json['tipo_oirs'] ?? 'Solicitud')
                                : \Illuminate\Support\Str::limit(optional($solicitud->tipo)->titulo, 32);
                        @endphp
                        <div class="flex items-center gap-3 px-5 py-3.5">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-900">{{ $titulo }}</p>
                                <p class="text-xs text-slate-500">{{ $solicitud->folio }} · {{ $solicitud->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="hidden shrink-0 rounded-full px-2.5 py-0.5 text-[11px] font-semibold sm:inline-flex"
                                  style="background-color: {{ $estadoLabel[1] }}; color: {{ $estadoLabel[2] }};">
                                {{ $estadoLabel[0] }}
                            </span>
                            <a href="{{ route('vecino.solicitud.show', $solicitud->id) }}"
                               class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-semibold text-white"
                               style="background-color: #0f2d5c;">Ver</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- BANNER INFERIOR --}}
    <section class="border-t" style="background-color: #f0f7ff; border-color: #dbeafe;">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-6 px-4 py-10 sm:px-6 lg:flex-row lg:justify-between lg:px-8">
            <div class="flex items-start gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full" style="background-color: #dbeafe; color: #1d4ed8;">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </span>
                <div>
                    <p class="text-lg font-bold" style="color: #0f2d5c;">Tu opinión nos importa</p>
                    <p class="mt-1 max-w-xl text-sm leading-relaxed" style="color: #475569;">
                        En la Municipalidad de Chanco trabajamos cada día para mejorar la calidad de vida de nuestra comunidad.
                    </p>
                </div>
            </div>
            <svg viewBox="0 0 200 80" class="hidden h-20 w-48 shrink-0 sm:block" fill="none" aria-hidden="true">
                <circle cx="40" cy="35" r="14" fill="#bfdbfe"/>
                <rect x="28" y="48" width="24" height="28" rx="8" fill="#93c5fd"/>
                <circle cx="80" cy="30" r="12" fill="#fde68a"/>
                <rect x="70" y="42" width="20" height="30" rx="6" fill="#fcd34d"/>
                <circle cx="120" cy="32" r="13" fill="#bbf7d0"/>
                <rect x="108" y="45" width="24" height="28" rx="8" fill="#86efac"/>
                <circle cx="160" cy="28" r="11" fill="#fecaca"/>
                <rect x="150" y="40" width="20" height="32" rx="6" fill="#fca5a5"/>
            </svg>
        </div>
    </section>
</div>
@endsection
