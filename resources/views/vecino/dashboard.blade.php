@extends('layouts.app')

@section('title', 'OIRS Digital')
@section('header_title', 'Inicio')

@php
    $nombreCompleto = trim(auth()->user()->name ?? '');
    $partesNombre = preg_split('/\s+/', $nombreCompleto, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $n = count($partesNombre);
    $saludoNombre = $n >= 3
        ? $partesNombre[0] . ' ' . $partesNombre[$n - 2]
        : ($n === 2 ? $partesNombre[0] . ' ' . $partesNombre[1] : ($partesNombre[0] ?? 'vecino'));

    $linkBase = $oirsTipoId
        ? route('vecino.iniciar-solicitud', $oirsTipoId)
        : route('vecino.solicitudes');

    $acciones = [
        ['key' => 'informacion', 'titulo' => 'Realizar una Consulta', 'desc' => 'Información sobre trámites, servicios y procedimientos municipales.', 'href' => $linkBase.'?tipo_oirs=informacion', 'accent' => '#2563eb', 'icobg' => '#eff6ff', 'icofg' => '#1d4ed8', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['key' => 'reclamo', 'titulo' => 'Presentar un Reclamo', 'desc' => 'Reporte problemas con servicios, atención o incumplimientos de la municipalidad.', 'href' => $linkBase.'?tipo_oirs=reclamo', 'accent' => '#ea580c', 'icobg' => '#fff7ed', 'icofg' => '#c2410c', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ['key' => 'sugerencia', 'titulo' => 'Enviar una Sugerencia', 'desc' => 'Proponga ideas para mejorar la gestión y los servicios de su comuna.', 'href' => $linkBase.'?tipo_oirs=sugerencia', 'accent' => '#16a34a', 'icobg' => '#f0fdf4', 'icofg' => '#15803d', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
        ['key' => 'felicitacion', 'titulo' => 'Enviar una Felicitación', 'desc' => 'Reconozca la buena atención y el servicio de funcionarios municipales.', 'href' => $linkBase.'?tipo_oirs=felicitacion', 'accent' => '#7c3aed', 'icobg' => '#f5f3ff', 'icofg' => '#6d28d9', 'icon' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5'],
        ['key' => 'seguimiento', 'titulo' => 'Seguimiento de Solicitudes', 'desc' => 'Revise el estado y avance de todas sus solicitudes ingresadas.', 'href' => route('vecino.mis-solicitudes'), 'accent' => '#0d9488', 'icobg' => '#f0fdfa', 'icofg' => '#0f766e', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
    ];
@endphp

@push('styles')
<style>
    .oirs-hero {
        background: linear-gradient(135deg, #e8f2ff 0%, #f8fbff 40%, #ffffff 100%);
        position: relative;
        overflow: hidden;
    }
    .oirs-hero::before {
        content: '';
        position: absolute;
        top: -120px; right: -80px;
        width: 420px; height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37,99,235,.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .oirs-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -40px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(15,45,92,.06) 0%, transparent 70%);
        pointer-events: none;
    }
    .oirs-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 6px 14px; border-radius: 9999px;
        background: rgba(255,255,255,.85);
        border: 1px solid rgba(37,99,235,.15);
        box-shadow: 0 1px 3px rgba(15,45,92,.06);
        font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        color: #1d4ed8;
    }
    .oirs-btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        padding: 14px 28px; border-radius: 14px;
        background: linear-gradient(135deg, #0f2d5c 0%, #1e4a7a 100%);
        color: #fff; font-size: 14px; font-weight: 700;
        box-shadow: 0 4px 14px rgba(15,45,92,.35);
        transition: transform .2s, box-shadow .2s;
        text-decoration: none;
    }
    .oirs-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,45,92,.4); color: #fff; }
    .oirs-btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        padding: 14px 28px; border-radius: 14px;
        background: #fff; color: #0f2d5c;
        border: 2px solid #0f2d5c;
        font-size: 14px; font-weight: 700;
        transition: background .2s, transform .2s;
        text-decoration: none;
    }
    .oirs-btn-secondary:hover { background: #f0f7ff; transform: translateY(-2px); color: #0f2d5c; }
    .oirs-stat {
        flex: 1; min-width: 100px;
        padding: 16px 20px; border-radius: 16px;
        background: rgba(255,255,255,.9);
        border: 1px solid rgba(226,232,240,.8);
        box-shadow: 0 2px 8px rgba(15,45,92,.04);
        text-align: center;
    }
    .oirs-stat-num { font-size: 28px; font-weight: 800; line-height: 1; color: #0f2d5c; }
    .oirs-stat-lbl { margin-top: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #64748b; }
    .oirs-illus-wrap {
        border-radius: 24px; overflow: hidden;
        box-shadow: 0 20px 50px rgba(15,45,92,.12);
        border: 1px solid rgba(255,255,255,.8);
    }
    .oirs-card {
        display: flex; flex-direction: column;
        height: 100%; padding: 24px; border-radius: 20px;
        background: #fff; border: 1px solid #e8eef5;
        box-shadow: 0 2px 8px rgba(15,45,92,.04);
        text-decoration: none; color: inherit;
        transition: transform .25s cubic-bezier(.4,0,.2,1), box-shadow .25s, border-color .25s;
        position: relative; overflow: hidden;
    }
    .oirs-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: var(--accent); opacity: 0;
        transition: opacity .25s;
    }
    .oirs-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(15,45,92,.12);
        border-color: #c7d9f0;
    }
    .oirs-card:hover::before { opacity: 1; }
    .oirs-card-icon {
        width: 52px; height: 52px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
    }
    .oirs-card-cta {
        margin-top: auto; padding-top: 16px;
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 700; color: #2563eb;
        transition: gap .2s;
    }
    .oirs-card:hover .oirs-card-cta { gap: 10px; }
    .oirs-step {
        text-align: center; padding: 20px 16px;
    }
    .oirs-step-num {
        width: 36px; height: 36px; margin: 0 auto 12px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #0f2d5c, #2563eb);
        color: #fff; font-size: 14px; font-weight: 800;
    }
    .oirs-trust {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; font-weight: 600; color: #475569;
    }
    .oirs-trust-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; }
</style>
@endpush

@section('content')
<div class="min-h-screen" style="background: #fafbfd;">

    {{-- ═══ HERO ═══ --}}
    <section class="oirs-hero border-b" style="border-color: #e2e8f0;">
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-10 lg:py-16">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div class="relative z-10">
                    <div class="oirs-badge">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        OIRS Digital · Municipalidad de Chanco
                    </div>

                    <h1 class="mt-5 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl" style="color: #0a2540;">
                        ¡Bienvenido/a,<br>
                        <span style="color: #1d4ed8;">{{ $saludoNombre }}</span>!
                    </h1>

                    <p class="mt-5 max-w-lg text-base leading-relaxed sm:text-lg" style="color: #475569;">
                        Portal oficial de la <strong>Oficina de Informaciones, Reclamos y Sugerencias</strong>.
                        Gestione sus trámites de forma digital, segura y con seguimiento en línea.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        <a href="{{ route('vecino.solicitudes') }}" class="oirs-btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Realizar Solicitud
                        </a>
                        <a href="{{ route('vecino.mis-solicitudes') }}" class="oirs-btn-secondary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Ver Mis Solicitudes
                        </a>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2">
                        <span class="oirs-trust"><span class="oirs-trust-dot"></span> Trámite 100% digital</span>
                        <span class="oirs-trust"><span class="oirs-trust-dot"></span> Identidad verificada</span>
                        <span class="oirs-trust"><span class="oirs-trust-dot"></span> Respuesta oficial</span>
                    </div>

                    <div class="mt-5 flex items-center gap-2 rounded-xl px-4 py-3 text-sm" style="background: rgba(255,255,255,.7); border: 1px solid #e2e8f0; color: #64748b; max-width: 400px;">
                        <svg class="h-4 w-4 shrink-0" style="color: #1d4ed8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Atención presencial: Lun a Vie, 08:30 – 14:00 hrs
                    </div>
                </div>

                <div class="relative z-10 hidden lg:block">
                    <div class="oirs-illus-wrap">
                        <svg viewBox="0 0 520 360" class="w-full" fill="none" aria-hidden="true">
                            <defs>
                                <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#dbeafe"/><stop offset="100%" stop-color="#eff6ff"/></linearGradient>
                                <linearGradient id="bldg" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#1e4a7a"/><stop offset="100%" stop-color="#0f2d5c"/></linearGradient>
                            </defs>
                            <rect width="520" height="360" fill="url(#sky)"/>
                            <circle cx="420" cy="70" r="40" fill="#fde68a" opacity=".95"/>
                            <ellipse cx="260" cy="330" rx="220" ry="20" fill="#93c5fd" opacity=".25"/>
                            <path d="M40 280 L40 120 L130 80 L220 120 L220 280 Z" fill="url(#bldg)"/>
                            <rect x="95" y="160" width="30" height="38" rx="3" fill="#fbbf24"/>
                            <rect x="95" y="205" width="30" height="75" rx="3" fill="#fcd34d"/>
                            <path d="M130 80 L130 48 L148 40 L166 48 L166 80 Z" fill="#dc2626"/>
                            <text x="148" y="68" text-anchor="middle" fill="#fff" font-size="10" font-weight="bold">CHANCO</text>
                            <rect x="240" y="210" width="100" height="70" rx="6" fill="url(#bldg)"/>
                            <path d="M240 210 L290 165 L340 210 Z" fill="#1e4a7a"/>
                            <rect x="258" y="230" width="22" height="28" rx="2" fill="#93c5fd" opacity=".8"/>
                            <rect x="290" y="230" width="22" height="28" rx="2" fill="#93c5fd" opacity=".8"/>
                            <rect x="258" y="262" width="54" height="18" rx="2" fill="#fcd34d"/>
                            <rect x="360" y="175" width="120" height="105" rx="6" fill="#2d5a8e"/>
                            <path d="M360 175 L420 125 L480 175 Z" fill="#1e4a7a"/>
                            <rect x="378" y="200" width="20" height="26" rx="2" fill="#bfdbfe"/>
                            <rect x="410" y="200" width="20" height="26" rx="2" fill="#bfdbfe"/>
                            <rect x="442" y="200" width="20" height="26" rx="2" fill="#bfdbfe"/>
                            <rect x="378" y="235" width="20" height="26" rx="2" fill="#bfdbfe"/>
                            <rect x="410" y="235" width="20" height="26" rx="2" fill="#bfdbfe"/>
                            <path d="M0 280 Q130 255 260 280 T520 280 L520 360 L0 360 Z" fill="#86efac" opacity=".35"/>
                            <ellipse cx="55" cy="268" rx="26" ry="36" fill="#22c55e" opacity=".65"/>
                            <ellipse cx="470" cy="262" rx="22" ry="32" fill="#22c55e" opacity=".65"/>
                            <rect x="175" y="268" width="60" height="7" rx="3.5" fill="#a8a29e"/>
                            <rect x="166" y="275" width="10" height="5" fill="#a8a29e"/>
                            <rect x="234" y="275" width="10" height="5" fill="#a8a29e"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="relative z-10 mt-10 flex flex-wrap gap-4">
                <div class="oirs-stat">
                    <div class="oirs-stat-num">{{ $stats['total'] ?? 0 }}</div>
                    <div class="oirs-stat-lbl">Total solicitudes</div>
                </div>
                <div class="oirs-stat">
                    <div class="oirs-stat-num" style="color: #b45309;">{{ $stats['pendientes'] ?? 0 }}</div>
                    <div class="oirs-stat-lbl">En trámite</div>
                </div>
                <div class="oirs-stat">
                    <div class="oirs-stat-num" style="color: #047857;">{{ $stats['respondida'] ?? 0 }}</div>
                    <div class="oirs-stat-lbl">Resueltas</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ ACCIONES ═══ --}}
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-10">
        <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-[.15em]" style="color: #2563eb;">Servicios disponibles</p>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl" style="color: #0a2540;">¿Qué necesitas hacer?</h2>
            <p class="mx-auto mt-3 max-w-2xl text-base" style="color: #64748b;">
                Seleccione el tipo de gestión que desea realizar. Su solicitud será registrada y derivada al área correspondiente.
            </p>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach($acciones as $accion)
                <a href="{{ $accion['href'] }}" class="oirs-card" style="--accent: {{ $accion['accent'] }};">
                    <div class="oirs-card-icon" style="background: {{ $accion['icobg'] }}; color: {{ $accion['icofg'] }};">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $accion['icon'] }}"></path>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-[15px] font-bold leading-snug" style="color: #0a2540;">{{ $accion['titulo'] }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed" style="color: #64748b;">{{ $accion['desc'] }}</p>
                    <span class="oirs-card-cta">
                        Comenzar
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ═══ CÓMO FUNCIONA ═══ --}}
    <section style="background: linear-gradient(180deg, #f8fafc 0%, #fff 100%); border-top: 1px solid #e8eef5; border-bottom: 1px solid #e8eef5;">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-10">
            <h3 class="text-center text-lg font-bold" style="color: #0a2540;">¿Cómo funciona?</h3>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="oirs-step">
                    <div class="oirs-step-num">1</div>
                    <p class="text-sm font-bold" style="color: #0a2540;">Elija el tipo</p>
                    <p class="mt-1 text-sm" style="color: #64748b;">Consulta, reclamo, sugerencia o felicitación</p>
                </div>
                <div class="oirs-step">
                    <div class="oirs-step-num">2</div>
                    <p class="text-sm font-bold" style="color: #0a2540;">Complete el formulario</p>
                    <p class="mt-1 text-sm" style="color: #64748b;">Sus datos se cargan automáticamente desde ClaveÚnica</p>
                </div>
                <div class="oirs-step">
                    <div class="oirs-step-num">3</div>
                    <p class="text-sm font-bold" style="color: #0a2540;">Reciba la respuesta</p>
                    <p class="mt-1 text-sm" style="color: #64748b;">Seguimiento en línea y notificación por correo</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ SOLICITUDES RECIENTES ═══ --}}
    @if($mis_solicitudes->count() > 0)
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-10">
            <div class="overflow-hidden rounded-2xl" style="background: #fff; border: 1px solid #e8eef5; box-shadow: 0 4px 20px rgba(15,45,92,.06);">
                <div class="flex items-center justify-between px-6 py-5" style="background: linear-gradient(90deg, #f0f7ff, #fff); border-bottom: 1px solid #e8eef5;">
                    <div>
                        <h3 class="text-base font-bold" style="color: #0a2540;">Mis solicitudes recientes</h3>
                        <p class="mt-0.5 text-xs" style="color: #64748b;">Últimas gestiones ingresadas en el portal</p>
                    </div>
                    <a href="{{ route('vecino.mis-solicitudes') }}" class="rounded-lg px-4 py-2 text-xs font-bold transition-opacity hover:opacity-80" style="background: #0f2d5c; color: #fff;">Ver todas</a>
                </div>
                <div>
                    @foreach($mis_solicitudes->take(4) as $solicitud)
                        @php
                            $est = match($solicitud->estado) {
                                'respondida' => ['Resuelta', '#d1fae5', '#065f46'],
                                'rechazada' => ['Rechazada', '#ffe4e6', '#9f1239'],
                                default => ['En trámite', '#fef3c7', '#92400e'],
                            };
                            $titulo = optional($solicitud->tipo)->codigo === 'OIRS'
                                ? 'OIRS · ' . \Illuminate\Support\Str::ucfirst($solicitud->datos_json['tipo_oirs'] ?? 'Solicitud')
                                : \Illuminate\Support\Str::limit(optional($solicitud->tipo)->titulo, 36);
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-slate-50/80" style="border-bottom: 1px solid #f1f5f9;">
                            <div class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-xl sm:flex" style="background: #eff6ff; color: #1d4ed8;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold" style="color: #0a2540;">{{ $titulo }}</p>
                                <p class="mt-0.5 text-xs" style="color: #94a3b8;">{{ $solicitud->folio }} · {{ $solicitud->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="hidden shrink-0 rounded-full px-3 py-1 text-[11px] font-bold sm:inline-flex" style="background: {{ $est[1] }}; color: {{ $est[2] }};">{{ $est[0] }}</span>
                            <a href="{{ route('vecino.solicitud.show', $solicitud->id) }}" class="shrink-0 rounded-lg px-4 py-2 text-xs font-bold text-white" style="background: #1d4ed8;">Detalle</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══ BANNER ═══ --}}
    <section style="background: linear-gradient(135deg, #0f2d5c 0%, #1e4a7a 50%, #2563eb 100%);">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-8 px-4 py-14 sm:px-6 lg:flex-row lg:justify-between lg:px-10">
            <div class="flex items-start gap-5 text-center lg:text-left">
                <span class="mx-auto inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl lg:mx-0" style="background: rgba(255,255,255,.15);">
                    <svg width="28" height="28" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-white">Tu opinión nos importa</p>
                    <p class="mt-2 max-w-lg text-sm leading-relaxed" style="color: rgba(255,255,255,.85);">
                        En la Municipalidad de Chanco trabajamos cada día para mejorar la calidad de vida de nuestra comunidad.
                        Su voz es fundamental para construir una mejor comuna.
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('vecino.solicitudes') }}" class="rounded-xl px-6 py-3 text-sm font-bold text-white transition-opacity hover:opacity-90" style="background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3);">
                    Nueva solicitud
                </a>
                <a href="{{ route('vecino.mis-solicitudes') }}" class="rounded-xl px-6 py-3 text-sm font-bold transition-opacity hover:opacity-90" style="background: #fff; color: #0f2d5c;">
                    Mis solicitudes
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
