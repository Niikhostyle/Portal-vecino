<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OIRS Digital') - Municipalidad de Chanco</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-slate-50 antialiased" x-data="{ menuOpen: false }">

@php
    $navMode = trim($__env->yieldContent('nav_mode')) ?: 'full';
    $useBurger = $navMode === 'burger';
    $currentRoute = request()->route()?->getName() ?? '';
    $navItems = [
        ['route' => 'dashboard', 'label' => 'Inicio', 'match' => ['dashboard'], 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'vecino.solicitudes', 'label' => 'Realizar Solicitud', 'match' => ['vecino.solicitudes', 'vecino.iniciar-solicitud'], 'icon' => 'M12 4v16m8-8H4'],
        ['route' => 'vecino.mis-solicitudes', 'label' => 'Mis Solicitudes', 'match' => ['vecino.mis-solicitudes', 'vecino.solicitud.show'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    ];
    if (\App\Models\Setting::enabled('recintos_enabled')) {
        $navItems[] = ['route' => 'recintos.calendario', 'label' => 'Recintos', 'match' => ['recintos.calendario'], 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'];
    }

    $userName = auth()->user()->name ?? 'Vecino';
    $nameParts = preg_split('/\s+/', trim($userName), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = strtoupper(
        substr($nameParts[0] ?? 'V', 0, 1) .
        substr($nameParts[1] ?? ($nameParts[0] ?? ''), 1, 1)
    );
@endphp

<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 shadow-sm backdrop-blur-md">

    {{-- FILA SUPERIOR --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center gap-3 sm:gap-4">

            {{-- Burger (móvil siempre; escritorio solo en modo solicitud) --}}
            <button type="button"
                    @click="menuOpen = true"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 {{ $useBurger ? '' : 'lg:hidden' }}"
                    aria-label="Abrir menú">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- Logo + Municipalidad --}}
            <a href="{{ route('dashboard') }}" class="flex min-w-0 shrink-0 items-center gap-2.5 sm:gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-900 to-blue-800 text-white shadow-md shadow-blue-900/20 sm:h-11 sm:w-11">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold leading-tight text-blue-950 sm:text-[15px]">Municipalidad de Chanco</p>
                    <p class="truncate text-[11px] text-slate-500 sm:text-xs">Portal Ciudadano</p>
                </div>
            </a>

            {{-- Separador vertical --}}
            <div class="hidden h-8 w-px bg-slate-200 md:block" aria-hidden="true"></div>

            {{-- OIRS móvil/tablet (en desktop va centrado absoluto) --}}
            <div class="min-w-0 flex-1 md:flex-none lg:hidden">
                <p class="truncate text-base font-extrabold tracking-tight text-blue-900">OIRS</p>
                <p class="hidden truncate text-[11px] leading-tight text-slate-500 md:block">
                    Oficina de Información, Reclamos y Sugerencias
                </p>
            </div>

            {{-- OIRS centrado en escritorio --}}
            <div class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-center lg:block">
                <p class="text-lg font-extrabold tracking-tight text-blue-900">OIRS</p>
                <p class="whitespace-nowrap text-xs text-slate-500">
                    Oficina de Información, Reclamos y Sugerencias
                </p>
            </div>

            {{-- Usuario --}}
            <div class="ml-auto flex shrink-0 items-center gap-2 sm:gap-3">
                <div class="hidden items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/80 py-1.5 pl-1.5 pr-3 md:flex">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-900 text-xs font-bold text-white">
                        {{ $initials }}
                    </span>
                    <div class="text-left">
                        <p class="whitespace-nowrap text-sm font-semibold leading-tight text-slate-800">{{ $userName }}</p>
                        <p class="text-[11px] text-slate-500">Vecino</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 sm:px-3.5 sm:py-2 sm:text-sm">
                        <svg class="h-4 w-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="hidden sm:inline">Salir</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($useBurger)
        {{-- Sub-barra modo solicitud --}}
        <div class="border-t border-slate-100 bg-gradient-to-r from-slate-50 to-blue-50/40">
            <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-2.5 sm:px-6 lg:px-8">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('vecino.mis-solicitudes') }}"
                   class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm font-semibold text-blue-700 transition hover:bg-white hover:shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver
                </a>
                <span class="h-4 w-px bg-slate-300" aria-hidden="true"></span>
                <span class="truncate text-sm font-semibold text-slate-700">@yield('header_title', 'Solicitud')</span>
            </div>
        </div>
    @else
        {{-- Nav horizontal centrada (solo desktop) --}}
        <nav class="hidden border-t border-slate-100 bg-white lg:block" aria-label="Navegación principal">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-stretch justify-center gap-2 xl:gap-4">
                    @foreach($navItems as $item)
                        @php $active = in_array($currentRoute, $item['match'], true); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="group relative flex items-center gap-2 px-5 py-3.5 text-sm font-semibold transition-colors {{ $active ? 'text-blue-800' : 'text-slate-600 hover:text-blue-700' }}">
                            <svg class="h-4 w-4 shrink-0 {{ $active ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                            </svg>
                            {{ $item['label'] }}
                            @if($active)
                                <span class="absolute inset-x-3 bottom-0 h-0.5 rounded-full bg-blue-600"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>
    @endif
</header>

{{-- DRAWER MENÚ --}}
<div x-show="menuOpen" x-cloak class="fixed inset-0 z-50" @keydown.escape.window="menuOpen = false">
    <div x-show="menuOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         @click="menuOpen = false"></div>

    <div x-show="menuOpen"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="absolute left-0 top-0 flex h-full w-[min(100vw-3rem,20rem)] flex-col bg-white shadow-2xl">

        {{-- Cabecera drawer --}}
        <div class="flex items-center justify-between border-b border-slate-200 bg-gradient-to-r from-blue-900 to-blue-800 px-5 py-4 text-white">
            <div>
                <p class="text-sm font-bold">Menú OIRS</p>
                <p class="text-[11px] text-blue-200">Portal Ciudadano</p>
            </div>
            <button type="button" @click="menuOpen = false"
                    class="rounded-lg p-2 text-blue-200 transition hover:bg-white/10 hover:text-white"
                    aria-label="Cerrar menú">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Perfil en drawer (móvil) --}}
        <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-5 py-4 md:hidden">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-900 text-sm font-bold text-white">{{ $initials }}</span>
            <div>
                <p class="text-sm font-semibold leading-snug text-slate-900">{{ $userName }}</p>
                <p class="text-xs text-slate-500">Vecino</p>
            </div>
        </div>

        {{-- Links --}}
        <nav class="flex-1 space-y-1 overflow-y-auto p-4" aria-label="Menú lateral">
            @foreach($navItems as $item)
                @php $active = in_array($currentRoute, $item['match'], true); @endphp
                <a href="{{ route($item['route']) }}" @click="menuOpen = false"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ $active ? 'bg-blue-50 text-blue-800 ring-1 ring-blue-100' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $active ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                        </svg>
                    </span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Footer drawer --}}
        <div class="border-t border-slate-200 p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</div>

<main>
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success') || session('error') || (isset($errors) && $errors->any()))
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
    Swal.fire({ icon: 'success', title: 'Éxito', text: {!! json_encode(session('success')) !!} });
    @elseif(session('error'))
    Swal.fire({ icon: 'error', title: 'Error', text: {!! json_encode(session('error')) !!} });
    @elseif(isset($errors) && $errors->any())
    (function(){ var errs = {!! json_encode(array_map('e', $errors->all())) !!}; Swal.fire({ icon: 'error', title: 'Errores de validación', html: '<ul class="text-left list-disc list-inside space-y-1">' + errs.map(function(e){ return '<li>' + e + '</li>'; }).join('') + '</ul>' }); })();
    @endif
});
</script>
@endif
<script>
window.confirmSwal = function(event, options) {
    if (event) event.preventDefault();
    const opts = typeof options === 'string' ? { text: options } : options;
    Swal.fire({
        title: opts.title || '¿Está seguro?',
        text: opts.text || '',
        icon: opts.icon || 'warning',
        showCancelButton: true,
        confirmButtonColor: opts.confirmColor || '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: opts.confirmText || 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => { if (result.isConfirmed && event && event.target) event.target.submit(); });
    return false;
};
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>
