@extends('layouts.portal-vecino')

@section('title', 'Inicio')
@section('nav_mode', 'full')

@php
    $partes = preg_split('/\s+/', trim(auth()->user()->name ?? ''), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $n = count($partes);
    $saludo = $n >= 2 ? $partes[0] : ($partes[0] ?? 'vecino');

    $linkBase = $oirsTipoId ? route('vecino.iniciar-solicitud', $oirsTipoId) : route('vecino.solicitudes');

    $acciones = [
        ['titulo' => 'Realizar una Consulta', 'desc' => 'Solicite información sobre trámites, servicios municipales o procedimientos administrativos.', 'href' => $linkBase.'?tipo_oirs=informacion', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['titulo' => 'Presentar un Reclamo', 'desc' => 'Informe sobre problemas con servicios municipales, atención deficiente o incumplimientos.', 'href' => $linkBase.'?tipo_oirs=reclamo', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ['titulo' => 'Enviar una Sugerencia', 'desc' => 'Comparta ideas y propuestas para mejorar los servicios y la gestión municipal.', 'href' => $linkBase.'?tipo_oirs=sugerencia', 'bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
        ['titulo' => 'Enviar una Felicitación', 'desc' => 'Reconozca el buen servicio o la atención recibida por funcionarios municipales.', 'href' => $linkBase.'?tipo_oirs=felicitacion', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5'],
        ['titulo' => 'Seguimiento de Solicitudes', 'desc' => 'Consulte el estado y avance de sus solicitudes ingresadas anteriormente.', 'href' => route('vecino.mis-solicitudes'), 'bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ];
@endphp

@section('content')
{{-- HERO --}}
<section class="bg-gradient-to-b from-blue-50 to-white">
    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-16">
        <div>
            <h1 class="text-4xl font-extrabold leading-tight text-blue-950 sm:text-5xl">
                ¡Bienvenido/a, {{ $saludo }}!
            </h1>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Este es el portal OIRS de la Municipalidad de Chanco. Aquí puede realizar consultas,
                presentar reclamos, enviar sugerencias y felicitaciones de manera rápida y segura.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('vecino.solicitudes') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition hover:bg-blue-800">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    Realizar Solicitud
                </a>
                <a href="{{ route('vecino.mis-solicitudes') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-blue-900 bg-white px-6 py-3.5 text-sm font-bold text-blue-900 transition hover:bg-blue-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Ver Mis Solicitudes
                </a>
            </div>

            <p class="mt-6 flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Horario de atención: Lunes a Viernes de 08:30 a 14:00 horas
            </p>
        </div>

        <div class="hidden justify-center lg:flex">
            <div class="overflow-hidden rounded-2xl shadow-xl shadow-blue-900/10 ring-1 ring-blue-100">
                <svg viewBox="0 0 480 320" class="h-auto w-full max-w-lg" aria-hidden="true">
                    <rect width="480" height="320" fill="#dbeafe"/>
                    <circle cx="400" cy="60" r="35" fill="#fde68a"/>
                    <path d="M50 260 L50 130 L140 95 L230 130 L230 260 Z" fill="#1e3a8a"/>
                    <rect x="105" y="170" width="28" height="35" rx="2" fill="#fbbf24"/>
                    <path d="M140 95 L140 65 L158 58 L176 65 L176 95 Z" fill="#dc2626"/>
                    <rect x="250" y="210" width="90" height="50" rx="4" fill="#1e40af"/>
                    <path d="M250 210 L295 170 L340 210 Z" fill="#1e3a8a"/>
                    <rect x="330" y="175" width="110" height="85" rx="4" fill="#2563eb"/>
                    <path d="M330 175 L385 130 L440 175 Z" fill="#1e3a8a"/>
                    <path d="M0 260 Q120 240 240 260 T480 260 L480 320 L0 320 Z" fill="#86efac" opacity=".45"/>
                    <ellipse cx="60" cy="250" rx="24" ry="32" fill="#22c55e" opacity=".7"/>
                    <ellipse cx="430" cy="245" rx="20" ry="28" fill="#22c55e" opacity=".7"/>
                </svg>
            </div>
        </div>
    </div>
</section>

{{-- ACCIONES --}}
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <h2 class="text-center text-2xl font-bold text-blue-950 sm:text-3xl">¿Qué necesitas hacer?</h2>

    <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        @foreach($acciones as $accion)
            <a href="{{ $accion['href'] }}"
               class="group flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-full {{ $accion['bg'] }} {{ $accion['text'] }}">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $accion['icon'] }}"></path>
                    </svg>
                </span>
                <h3 class="mt-5 text-base font-bold leading-snug text-blue-950">{{ $accion['titulo'] }}</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">{{ $accion['desc'] }}</p>
                <span class="mt-4 inline-flex justify-end text-blue-600 transition group-hover:translate-x-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            </a>
        @endforeach
    </div>
</section>

{{-- RESUMEN RÁPIDO --}}
@if(($stats['total'] ?? 0) > 0)
<section class="border-y border-slate-100 bg-slate-50">
    <div class="mx-auto grid max-w-7xl grid-cols-3 gap-4 px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-white p-5 text-center shadow-sm ring-1 ring-slate-200">
            <p class="text-3xl font-extrabold text-blue-950">{{ $stats['total'] }}</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Total</p>
        </div>
        <div class="rounded-2xl bg-white p-5 text-center shadow-sm ring-1 ring-amber-200">
            <p class="text-3xl font-extrabold text-amber-700">{{ $stats['pendientes'] }}</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-amber-800">En trámite</p>
        </div>
        <div class="rounded-2xl bg-white p-5 text-center shadow-sm ring-1 ring-emerald-200">
            <p class="text-3xl font-extrabold text-emerald-700">{{ $stats['respondida'] }}</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-emerald-800">Resueltas</p>
        </div>
    </div>
</section>
@endif

{{-- BANNER --}}
<section class="bg-blue-50">
    <div class="mx-auto flex max-w-7xl flex-col items-center gap-8 px-4 py-12 sm:px-6 lg:flex-row lg:justify-between lg:px-8">
        <div class="flex items-start gap-4">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </span>
            <div>
                <p class="text-xl font-bold text-blue-950">Tu opinión nos importa</p>
                <p class="mt-2 max-w-xl text-sm leading-relaxed text-slate-600">
                    En la Municipalidad de Chanco trabajamos cada día para mejorar la calidad de vida de nuestra comunidad.
                </p>
            </div>
        </div>
        <svg viewBox="0 0 200 80" class="hidden h-20 w-48 shrink-0 sm:block" aria-hidden="true">
            <circle cx="40" cy="35" r="14" fill="#bfdbfe"/>
            <rect x="28" y="48" width="24" height="28" rx="8" fill="#93c5fd"/>
            <circle cx="85" cy="30" r="12" fill="#fde68a"/>
            <rect x="75" y="42" width="20" height="30" rx="6" fill="#fcd34d"/>
            <circle cx="130" cy="32" r="13" fill="#bbf7d0"/>
            <rect x="118" y="45" width="24" height="28" rx="8" fill="#86efac"/>
            <circle cx="170" cy="28" r="11" fill="#fecaca"/>
            <rect x="160" y="40" width="20" height="32" rx="6" fill="#fca5a5"/>
        </svg>
    </div>
</section>
@endsection
