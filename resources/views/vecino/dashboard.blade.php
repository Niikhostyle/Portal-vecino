@extends('layouts.portal-vecino')

@section('title', 'Inicio')
@section('nav_mode', 'full')

@php
    $partes = preg_split('/\s+/', trim(auth()->user()->name ?? ''), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $n = count($partes);
    $saludo = $n >= 2 ? $partes[0] : ($partes[0] ?? 'vecino');

    $linkBase = $oirsTipoId ? route('vecino.iniciar-solicitud', $oirsTipoId) : route('vecino.solicitudes');

    $dashImg = function (string $path): ?string {
        return file_exists(public_path($path)) ? asset($path) : null;
    };

    $heroImg = $dashImg('img/dashboard/hero.jpg');
    $bannerImg = $dashImg('img/dashboard/banner-tradicion.jpg');

    $acciones = [
        [
            'titulo' => 'Realizar una consulta',
            'desc' => 'Solicite información sobre trámites, servicios municipales o procedimientos.',
            'href' => $linkBase.'?tipo_oirs=informacion',
            'circle' => 'bg-blue-600',
            'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'titulo' => 'Realizar un reclamo',
            'desc' => 'Informe problemas con servicios municipales o atención deficiente.',
            'href' => $linkBase.'?tipo_oirs=reclamo',
            'circle' => 'bg-emerald-600',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        ],
        [
            'titulo' => 'Realizar una sugerencia',
            'desc' => 'Comparta ideas para mejorar los servicios y la gestión municipal.',
            'href' => $linkBase.'?tipo_oirs=sugerencia',
            'circle' => 'bg-amber-500',
            'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
        ],
        [
            'titulo' => 'Realizar una felicitación',
            'desc' => 'Reconozca el buen servicio recibido por funcionarios municipales.',
            'href' => $linkBase.'?tipo_oirs=felicitacion',
            'circle' => 'bg-violet-600',
            'icon' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5',
        ],
        [
            'titulo' => 'Seguimiento de solicitudes',
            'desc' => 'Consulte el estado y avance de sus solicitudes ingresadas.',
            'href' => route('vecino.mis-solicitudes'),
            'circle' => 'bg-teal-600',
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ],
    ];

    $enlacesRapidos = [
        ['label' => 'Portal de Transparencia Activa', 'href' => 'https://www.portaltransparencia.cl/PortalPdT/pdtta?codOrganismo=MU037'],
        ['label' => 'Turismo', 'href' => 'https://chanco.cl/turismo/'],
        ['label' => 'organigrama Municipal', 'href' => 'https://organigrama.chanco.cl/'],
        ['label' => 'Teléfonos y Contactos', 'href' => 'https://chanco.cl/nueva-numeracion-telefonica-municipal/'],
    ];
@endphp

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative overflow-hidden" aria-labelledby="hero-titulo">
    {{-- Fondo imagen o placeholder --}}
    <div class="absolute inset-0" aria-hidden="true">
        @if($heroImg)
            <img src="{{ $heroImg }}" alt="" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-200/60 via-sky-200/50 to-blue-300/40">
                <div class="mx-4 rounded-2xl border-2 border-dashed border-white/60 bg-white/30 px-8 py-6 text-center backdrop-blur-sm">
                    <svg class="mx-auto h-10 w-10 text-slate-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-slate-600">Imagen de fondo del hero</p>
                    <p class="mt-1 text-xs text-slate-500">Cargar en <code class="rounded bg-white/50 px-1">public/img/dashboard/hero.jpg</code></p>
                </div>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-white/25 sm:via-white/85 sm:to-transparent"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="z-10 max-w-xl">
            <h1 id="hero-titulo" class="text-4xl font-extrabold leading-tight tracking-tight text-blue-950 sm:text-5xl lg:text-[3.25rem]">
                Bienvenido/a, {{ $saludo }}
            </h1>
            <p class="mt-3 text-lg font-semibold text-blue-700 sm:text-xl">
                Portal OIRS de la Municipalidad de Chanco
            </p>
            <p class="mt-4 max-w-lg text-base leading-relaxed text-slate-600">
                Aquí puedes realizar consultas, reclamos, sugerencias y felicitaciones de forma fácil y rápida.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('vecino.solicitudes') }}"
                   class="inline-flex items-center justify-center gap-2.5 rounded-lg bg-blue-900 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-900/25 transition hover:bg-blue-800 hover:shadow-xl">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Realizar una solicitud
                </a>
                <a href="{{ route('vecino.mis-solicitudes') }}"
                   class="inline-flex items-center justify-center gap-2.5 rounded-lg border-2 border-blue-900 bg-white px-6 py-3.5 text-sm font-bold text-blue-900 transition hover:bg-blue-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Ver mis solicitudes
                </a>
            </div>

            <p class="mt-6 flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Horario de atención: Lunes a Viernes de 09:00 a 17:00 horas
            </p>
        </div>
    </div>
</section>

{{-- ═══ ¿QUÉ NECESITAS HACER? ═══ --}}
<section class="bg-white py-14 sm:py-16" aria-labelledby="acciones-titulo">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 id="acciones-titulo" class="text-center text-2xl font-bold text-blue-950 sm:text-3xl">¿Qué necesitas hacer?</h2>

        <ul class="mt-10 grid list-none grid-cols-1 gap-5 p-0 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach($acciones as $accion)
                <li>
                <a href="{{ $accion['href'] }}"
                   class="group relative flex min-h-[200px] flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full {{ $accion['circle'] }} text-white shadow-md" aria-hidden="true">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $accion['icon'] }}"></path>
                        </svg>
                    </span>
                    <span class="mt-5 block text-base font-bold leading-snug text-blue-950">{{ $accion['titulo'] }}</span>
                    <span class="mt-2 block flex-1 text-sm leading-relaxed text-slate-500">{{ $accion['desc'] }}</span>
                    <span class="mt-4 flex justify-end text-blue-600 opacity-60 transition group-hover:translate-x-1 group-hover:opacity-100" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ═══ SECCIÓN INFERIOR: BANNER + ENLACES + NOTICIAS ═══ --}}
<section class="border-t border-slate-200 bg-slate-50 py-12 sm:py-14">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-4 sm:px-6 lg:grid-cols-12 lg:gap-6 lg:px-8">

        {{-- Banner tradición --}}
        <div class="lg:col-span-4">
            <div class="overflow-hidden rounded-2xl bg-black shadow-md ring-1 ring-slate-200/80">
                @if($bannerImg)
                    <img src="{{ $bannerImg }}"
                         alt="Chanco — Ilustre Municipalidad, tradición que nos une"
                         class="block h-auto w-full object-contain">
                @else
                    <div class="relative">
                        <div class="flex aspect-[4/3] w-full flex-col items-center justify-center bg-gradient-to-br from-emerald-100 via-sky-100 to-blue-100 p-6">
                            <svg class="h-12 w-12 text-slate-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-3 text-center text-sm font-medium text-slate-500">Banner decorativo</p>
                            <p class="mt-1 text-center text-xs text-slate-400"><code class="rounded bg-white/60 px-1">public/img/dashboard/banner-tradicion.jpg</code></p>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-950/70 via-transparent to-transparent"></div>
                        <p class="absolute bottom-5 left-5 right-5 font-serif text-2xl font-bold italic leading-tight text-white drop-shadow-lg sm:text-3xl">
                            Chanco — Tradición que nos une
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Enlaces rápidos --}}
        <div class="lg:col-span-3">
            <h3 class="text-lg font-bold text-blue-950">Enlaces rápidos</h3>
            <ul class="mt-5 space-y-1">
                @foreach($enlacesRapidos as $enlace)
                    <li>
                        <a href="{{ $enlace['href'] }}"
                           class="group flex items-center justify-between rounded-xl px-3 py-3 text-sm font-medium text-blue-700 transition hover:bg-white hover:shadow-sm">
                            <span>{{ $enlace['label'] }}</span>
                            <svg class="h-4 w-4 shrink-0 text-blue-400 transition group-hover:translate-x-0.5 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Noticias destacadas (WordPress chanco.cl) --}}
        <div class="lg:col-span-5">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-blue-950">Noticias destacadas</h3>
                <a href="{{ $noticiasVerTodasUrl }}" target="_blank" rel="noopener noreferrer"
                   class="text-sm font-semibold text-blue-600 transition hover:text-blue-800">
                    Ver todas
                </a>
            </div>

            @if($noticias->isNotEmpty())
                <div class="mt-5 space-y-4">
                    @foreach($noticias as $noticia)
                        <article class="group relative flex gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm transition hover:shadow-md">
                            <div class="h-20 w-24 shrink-0 overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200">
                                @if(!empty($noticia['img']))
                                    <img src="{{ $noticia['img'] }}" alt="" class="h-full w-full object-cover" loading="lazy" aria-hidden="true">
                                @else
                                    <div class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-slate-100 p-2" aria-hidden="true">
                                        <img src="{{ asset('img/logo1.png') }}" alt="" class="h-8 w-auto max-w-full object-contain opacity-70">
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                @if(!empty($noticia['fecha']))
                                    <time class="text-[11px] font-bold uppercase tracking-wider text-blue-600" datetime="{{ $noticia['fecha'] }}">{{ $noticia['fecha'] }}</time>
                                @endif
                                <h4 class="mt-1 text-sm font-bold leading-snug text-slate-800 group-hover:text-blue-900">
                                    <a href="{{ $noticia['url'] }}" target="_blank" rel="noopener noreferrer"
                                       class="after:absolute after:inset-0 after:content-['']">
                                        {{ $noticia['titulo'] }}
                                    </a>
                                </h4>
                                <span class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-600">Leer más en chanco.cl</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-white p-6 text-center">
                    <p class="text-sm text-slate-500">No fue posible cargar las noticias en este momento.</p>
                    <a href="{{ $noticiasVerTodasUrl }}" target="_blank" rel="noopener noreferrer"
                       class="mt-2 inline-block text-sm font-semibold text-blue-600 hover:text-blue-800">
                        Ver noticias en chanco.cl
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══ FOOTER INSTITUCIONAL ═══ --}}
<footer class="border-t border-slate-200 bg-blue-950 py-8 text-white" role="contentinfo">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 text-center sm:flex-row sm:px-6 sm:text-left lg:px-8">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo1.png') }}" alt="Municipalidad de Chanco" class="h-10 w-auto object-contain brightness-0 invert opacity-90" onerror="this.style.display='none'">
            <div>
                <p class="text-sm font-semibold">Ilustre Municipalidad de Chanco</p>
                <p class="text-xs text-blue-200">Portal Ciudadano · OIRS Digital</p>
            </div>
        </div>
        <p class="text-xs text-blue-300">© {{ date('Y') }} Municipalidad de Chanco. Todos los derechos reservados.</p>
    </div>
</footer>

@endsection
