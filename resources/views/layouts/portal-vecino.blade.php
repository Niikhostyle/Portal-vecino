<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OIRS Digital') - Municipalidad de Chanco</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>
        (function () {
            var scale = localStorage.getItem('portal-a11y-font');
            if (scale) document.documentElement.style.fontSize = (parseFloat(scale) * 100) + '%';
            if (localStorage.getItem('portal-a11y-contrast') === '1') document.documentElement.classList.add('a11y-high-contrast');
            if (localStorage.getItem('portal-a11y-underline') === '1') document.documentElement.classList.add('a11y-underline-links');
            if (localStorage.getItem('portal-a11y-focus') === '1') document.documentElement.classList.add('a11y-focus-visible');
        })();
    </script>
</head>
<body class="min-h-full bg-slate-50 antialiased"
      x-data="{
          menuOpen: false,
          searchOpen: false,
          a11yOpen: false,
          searchQuery: '{{ request('folio', '') }}',
          fontScale: parseFloat(localStorage.getItem('portal-a11y-font') || '1'),
          highContrast: localStorage.getItem('portal-a11y-contrast') === '1',
          underlineLinks: localStorage.getItem('portal-a11y-underline') === '1',
          enhancedFocus: localStorage.getItem('portal-a11y-focus') === '1',
          screenReaderActive: false,
          misSolicitudesUrl: @js(route('vecino.mis-solicitudes')),
          fontPercent() {
              return Math.round(this.fontScale * 100) + '%';
          },
          increaseFont() {
              this.fontScale = Math.min(1.4, Math.round((this.fontScale + 0.1) * 10) / 10);
              this.applyFont();
          },
          decreaseFont() {
              this.fontScale = Math.max(0.9, Math.round((this.fontScale - 0.1) * 10) / 10);
              this.applyFont();
          },
          resetFont() {
              this.fontScale = 1;
              this.applyFont();
          },
          applyFont() {
              document.documentElement.style.fontSize = (this.fontScale * 100) + '%';
              localStorage.setItem('portal-a11y-font', String(this.fontScale));
          },
          toggleContrast() {
              this.highContrast = !this.highContrast;
              document.documentElement.classList.toggle('a11y-high-contrast', this.highContrast);
              localStorage.setItem('portal-a11y-contrast', this.highContrast ? '1' : '0');
          },
          toggleUnderline() {
              this.underlineLinks = !this.underlineLinks;
              document.documentElement.classList.toggle('a11y-underline-links', this.underlineLinks);
              localStorage.setItem('portal-a11y-underline', this.underlineLinks ? '1' : '0');
          },
          toggleFocus() {
              this.enhancedFocus = !this.enhancedFocus;
              document.documentElement.classList.toggle('a11y-focus-visible', this.enhancedFocus);
              localStorage.setItem('portal-a11y-focus', this.enhancedFocus ? '1' : '0');
          },
          getReadableText() {
              var main = document.querySelector('main');
              if (!main) return '';
              var clone = main.cloneNode(true);
              clone.querySelectorAll('script, style, [aria-hidden=true], svg').forEach(function(el) { el.remove(); });
              return clone.innerText.replace(/\s+/g, ' ').trim();
          },
          speakWithBestVoice(utterance) {
              var voices = window.speechSynthesis.getVoices();
              var es = voices.find(function(v) { return v.lang && v.lang.toLowerCase().startsWith('es'); });
              if (es) utterance.voice = es;
              window.speechSynthesis.speak(utterance);
          },
          toggleScreenReader() {
              if (this.screenReaderActive) {
                  window.speechSynthesis.cancel();
                  this.screenReaderActive = false;
                  return;
              }
              if (!('speechSynthesis' in window)) {
                  alert('Su navegador no admite lectura en voz alta. Pruebe con Chrome o Edge.');
                  return;
              }
              var text = this.getReadableText();
              if (!text) {
                  alert('No hay contenido para leer en esta página.');
                  return;
              }
              var self = this;
              window.speechSynthesis.cancel();
              var utterance = new SpeechSynthesisUtterance(text);
              utterance.lang = 'es-CL';
              utterance.rate = 0.92;
              utterance.onend = function() { self.screenReaderActive = false; };
              utterance.onerror = function() { self.screenReaderActive = false; };
              this.screenReaderActive = true;
              if (window.speechSynthesis.getVoices().length) {
                  this.speakWithBestVoice(utterance);
              } else {
                  window.speechSynthesis.onvoiceschanged = function() {
                      self.speakWithBestVoice(utterance);
                  };
              }
          },
          resetA11y() {
              window.speechSynthesis.cancel();
              this.screenReaderActive = false;
              this.fontScale = 1;
              this.highContrast = false;
              this.underlineLinks = false;
              this.enhancedFocus = false;
              this.applyFont();
              document.documentElement.classList.remove('a11y-high-contrast', 'a11y-underline-links', 'a11y-focus-visible');
              localStorage.removeItem('portal-a11y-contrast');
              localStorage.removeItem('portal-a11y-underline');
              localStorage.removeItem('portal-a11y-focus');
          },
          submitSearch() {
              var q = this.searchQuery.trim();
              if (!q) return;
              window.location.href = this.misSolicitudesUrl + '?folio=' + encodeURIComponent(q);
          }
      }"
      @keydown.escape.window="menuOpen = false; searchOpen = false; a11yOpen = false">

<a href="#portal-main-content" class="sr-only">Saltar al contenido principal</a>

@php
    $navMode = trim($__env->yieldContent('nav_mode')) ?: 'full';
    $useBurger = $navMode === 'burger';
    $currentRoute = request()->route()?->getName() ?? '';

    $navMenu = [
        ['type' => 'search', 'label' => 'Buscar', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
        ['type' => 'link', 'route' => 'dashboard', 'label' => 'Inicio', 'match' => ['dashboard'], 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['type' => 'link', 'route' => 'vecino.solicitudes', 'label' => 'Realizar Solicitud', 'match' => ['vecino.solicitudes', 'vecino.iniciar-solicitud'], 'icon' => 'M12 4v16m8-8H4'],
        ['type' => 'link', 'route' => 'vecino.mis-solicitudes', 'label' => 'Mis Solicitudes', 'match' => ['vecino.mis-solicitudes', 'vecino.solicitud.show'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['type' => 'accessibility', 'label' => 'Accesibilidad', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];

    if (\App\Models\Setting::enabled('recintos_enabled')) {
        $navMenu[] = ['type' => 'link', 'route' => 'recintos.calendario', 'label' => 'Recintos', 'match' => ['recintos.calendario'], 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'];
    }

    $userName = auth()->user()->name ?? 'Vecino';
    $nameParts = preg_split('/\s+/', trim($userName), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = strtoupper(
        substr($nameParts[0] ?? 'V', 0, 1) .
        substr($nameParts[1] ?? ($nameParts[0] ?? ''), 1, 1)
    );
@endphp

<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 shadow-sm backdrop-blur-md" role="banner">

    {{-- FILA SUPERIOR --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center gap-3 sm:gap-4">

            <button type="button"
                    @click="menuOpen = true"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 {{ $useBurger ? '' : 'lg:hidden' }}"
                    aria-label="Abrir menú">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" class="flex min-w-0 shrink-0 flex-col items-start gap-0.5">
                <img src="{{ asset('img/logo1.png') }}"
                     alt="Ilustre Municipalidad de Chanco"
                     class="h-8 w-auto max-w-[9rem] object-contain object-left sm:h-9 sm:max-w-[10.5rem]" />
                <p class="text-[10px] font-medium leading-none text-slate-500 sm:text-[11px]">Portal Ciudadano</p>
            </a>

            <div class="hidden h-8 w-px bg-slate-200 md:block" aria-hidden="true"></div>

            <div class="min-w-0 flex-1 md:flex-none lg:hidden">
                <p class="truncate text-base font-extrabold tracking-tight text-blue-900">OIRS</p>
                <p class="hidden truncate text-[11px] leading-tight text-slate-500 md:block">
                    Oficina de Información, Reclamos y Sugerencias
                </p>
            </div>

            <div class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-center lg:block" aria-hidden="true">
                <p class="text-lg font-extrabold tracking-tight text-blue-900">OIRS</p>
                <p class="whitespace-nowrap text-xs text-slate-500">
                    Oficina de Información, Reclamos y Sugerencias
                </p>
            </div>

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
                            aria-label="Cerrar sesión"
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
        <nav class="relative hidden border-t border-slate-100 bg-white lg:block" aria-label="Navegación principal">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-stretch justify-center gap-1 xl:gap-3">
                    @foreach($navMenu as $item)
                        @if($item['type'] === 'link')
                            @php $active = in_array($currentRoute, $item['match'], true); @endphp
                            <a href="{{ route($item['route']) }}"
                               @if($active) aria-current="page" @endif
                               class="group relative flex items-center gap-2 px-4 py-3.5 text-sm font-semibold transition-colors {{ $active ? 'text-blue-800' : 'text-slate-600 hover:text-blue-700' }}">
                                <svg class="h-4 w-4 shrink-0 {{ $active ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                                </svg>
                                {{ $item['label'] }}
                                @if($active)
                                    <span class="absolute inset-x-2 bottom-0 h-0.5 rounded-full bg-blue-600"></span>
                                @endif
                            </a>
                        @elseif($item['type'] === 'search')
                            <div class="relative" @click.outside="searchOpen = false">
                                <button type="button"
                                        @click="searchOpen = !searchOpen; a11yOpen = false"
                                        :class="searchOpen ? 'text-blue-800' : 'text-slate-600 hover:text-blue-700'"
                                        class="group flex items-center gap-2 px-4 py-3.5 text-sm font-semibold transition-colors"
                                        aria-controls="nav-search-panel"
                                        :aria-expanded="searchOpen ? 'true' : 'false'">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                                    </svg>
                                    {{ $item['label'] }}
                                </button>
                                <div x-show="searchOpen" x-cloak x-transition id="nav-search-panel"
                                     class="absolute left-1/2 top-full z-50 mt-1 w-80 -translate-x-1/2 rounded-xl border border-slate-200 bg-white p-4 shadow-xl"
                                     role="search">
                                    <label for="nav-search-folio" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Buscar solicitud por folio</label>
                                    <form @submit.prevent="submitSearch()" class="flex gap-2">
                                        <input type="search" id="nav-search-folio" x-model="searchQuery"
                                               placeholder="Ej: CHANCO-2024-000001"
                                               autocomplete="off"
                                               class="h-10 flex-1 rounded-lg border border-slate-300 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                        <button type="submit"
                                                class="shrink-0 rounded-lg bg-blue-900 px-4 text-sm font-semibold text-white hover:bg-blue-800">
                                            Ir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif($item['type'] === 'accessibility')
                            <div class="relative" @click.outside="a11yOpen = false">
                                <button type="button"
                                        @click="a11yOpen = !a11yOpen; searchOpen = false"
                                        :class="a11yOpen ? 'text-blue-800' : 'text-slate-600 hover:text-blue-700'"
                                        class="group flex items-center gap-2 px-4 py-3.5 text-sm font-semibold transition-colors"
                                        aria-label="Opciones de accesibilidad"
                                        aria-controls="nav-a11y-panel"
                                        :aria-expanded="a11yOpen ? 'true' : 'false'">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                                    </svg>
                                    {{ $item['label'] }}
                                </button>
                                <div x-show="a11yOpen" x-cloak x-transition id="nav-a11y-panel"
                                     class="absolute right-0 top-full z-50 mt-1 w-80 rounded-xl border border-slate-200 bg-white p-4 shadow-xl"
                                     role="dialog" aria-label="Opciones de accesibilidad">
                                    <p class="mb-2 text-sm font-bold text-blue-950">Opciones de accesibilidad</p>
                                    <p class="mb-3 rounded-lg bg-blue-50 p-3 text-xs leading-relaxed text-slate-600">
                                        Si usa lector de pantalla del sistema (NVDA, VoiceOver, JAWS), navegue con <kbd class="rounded border border-slate-300 bg-white px-1">Tab</kbd> y las flechas. El botón «Leer en voz alta» es un complemento, no reemplaza su lector.
                                    </p>
                                    <div class="space-y-2">
                                        <p class="text-xs font-semibold text-slate-500">Tamaño de texto</p>
                                        <div class="flex gap-2">
                                            <button type="button" @click="decreaseFont()"
                                                    class="flex-1 rounded-lg border border-slate-200 py-2 text-sm font-semibold hover:bg-slate-50"
                                                    aria-label="Disminuir tamaño de texto">A−</button>
                                            <button type="button" @click="resetFont()"
                                                    class="flex-1 rounded-lg border border-slate-200 py-2 text-xs font-semibold hover:bg-slate-50"
                                                    :aria-label="'Tamaño actual ' + fontPercent() + ', clic para restablecer al 100%'"
                                                    x-text="fontPercent()"></button>
                                            <button type="button" @click="increaseFont()"
                                                    class="flex-1 rounded-lg border border-slate-200 py-2 text-sm font-semibold hover:bg-slate-50"
                                                    aria-label="Aumentar tamaño de texto">A+</button>
                                        </div>
                                        <button type="button" @click="toggleScreenReader()"
                                                :class="screenReaderActive ? 'border-blue-600 bg-blue-50 text-blue-800' : 'border-slate-200 hover:bg-slate-50'"
                                                class="flex w-full items-center justify-between rounded-lg border px-3 py-2.5 text-sm font-medium transition">
                                            <span class="flex items-center gap-2">
                                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414m2.828-9.9a9 9 0 0112.728 0"></path>
                                                </svg>
                                                Leer en voz alta
                                            </span>
                                            <span class="text-xs" x-text="screenReaderActive ? 'Detener' : 'Iniciar'"></span>
                                        </button>
                                        <button type="button" @click="toggleContrast()"
                                                :class="highContrast ? 'border-blue-600 bg-blue-50 text-blue-800' : 'border-slate-200 hover:bg-slate-50'"
                                                class="flex w-full items-center justify-between rounded-lg border px-3 py-2.5 text-sm font-medium transition">
                                            Alto contraste
                                            <span class="text-xs" x-text="highContrast ? 'Activado' : 'Desactivado'"></span>
                                        </button>
                                        <button type="button" @click="toggleUnderline()"
                                                :class="underlineLinks ? 'border-blue-600 bg-blue-50 text-blue-800' : 'border-slate-200 hover:bg-slate-50'"
                                                class="flex w-full items-center justify-between rounded-lg border px-3 py-2.5 text-sm font-medium transition">
                                            Subrayar enlaces
                                            <span class="text-xs" x-text="underlineLinks ? 'Activado' : 'Desactivado'"></span>
                                        </button>
                                        <button type="button" @click="toggleFocus()"
                                                :class="enhancedFocus ? 'border-blue-600 bg-blue-50 text-blue-800' : 'border-slate-200 hover:bg-slate-50'"
                                                class="flex w-full items-center justify-between rounded-lg border px-3 py-2.5 text-sm font-medium transition">
                                            Resaltar foco
                                            <span class="text-xs" x-text="enhancedFocus ? 'Activado' : 'Desactivado'"></span>
                                        </button>
                                        <button type="button" @click="resetA11y()"
                                                class="w-full rounded-lg py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                                            Restablecer todo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
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
         class="absolute left-0 top-0 flex h-full w-[min(100vw-3rem,20rem)] flex-col bg-white shadow-2xl"
         role="dialog" aria-modal="true" aria-label="Menú de navegación">

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

        <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-5 py-4 md:hidden">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-900 text-sm font-bold text-white">{{ $initials }}</span>
            <div>
                <p class="text-sm font-semibold leading-snug text-slate-900">{{ $userName }}</p>
                <p class="text-xs text-slate-500">Vecino</p>
            </div>
        </div>

        {{-- Búsqueda en drawer --}}
        <div class="border-b border-slate-100 p-4">
            <label for="drawer-search-folio" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Buscar por folio</label>
            <form @submit.prevent="submitSearch(); menuOpen = false" class="flex gap-2" role="search">
                <input type="search" id="drawer-search-folio" x-model="searchQuery"
                       placeholder="N° de folio"
                       class="h-10 flex-1 rounded-lg border border-slate-300 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                <button type="submit" class="rounded-lg bg-blue-900 px-3 text-sm font-semibold text-white">Ir</button>
            </form>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-4" aria-label="Menú lateral">
            @foreach($navMenu as $item)
                @if($item['type'] === 'link')
                    @php $active = in_array($currentRoute, $item['match'], true); @endphp
                    <a href="{{ route($item['route']) }}" @click="menuOpen = false"
                       @if($active) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ $active ? 'bg-blue-50 text-blue-800 ring-1 ring-blue-100' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $active ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                            </svg>
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Accesibilidad en drawer --}}
        <div class="border-t border-slate-200 p-4">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Accesibilidad</p>
            <div class="space-y-2">
                <div class="flex gap-2">
                    <button type="button" @click="decreaseFont()" class="flex-1 rounded-lg border border-slate-200 py-2 text-sm font-semibold">A−</button>
                    <button type="button" @click="resetFont()"
                            class="flex-1 rounded-lg border border-slate-200 py-2 text-xs font-semibold"
                            x-text="fontPercent()"></button>
                    <button type="button" @click="increaseFont()" class="flex-1 rounded-lg border border-slate-200 py-2 text-sm font-semibold">A+</button>
                </div>
                <button type="button" @click="toggleScreenReader()"
                        :class="screenReaderActive ? 'bg-blue-50 border-blue-200 text-blue-800' : 'border-slate-200'"
                        class="w-full rounded-lg border px-3 py-2 text-sm font-medium"
                        x-text="screenReaderActive ? 'Detener lectura en voz alta' : 'Leer en voz alta'"></button>
                <button type="button" @click="toggleContrast()"
                        :class="highContrast ? 'bg-blue-50 border-blue-200 text-blue-800' : 'border-slate-200'"
                        class="w-full rounded-lg border px-3 py-2 text-sm font-medium">Alto contraste</button>
                <button type="button" @click="toggleUnderline()"
                        :class="underlineLinks ? 'bg-blue-50 border-blue-200 text-blue-800' : 'border-slate-200'"
                        class="w-full rounded-lg border px-3 py-2 text-sm font-medium">Subrayar enlaces</button>
                <button type="button" @click="toggleFocus()"
                        :class="enhancedFocus ? 'bg-blue-50 border-blue-200 text-blue-800' : 'border-slate-200'"
                        class="w-full rounded-lg border px-3 py-2 text-sm font-medium">Resaltar foco</button>
            </div>
        </div>

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

<main id="portal-main-content" role="main" tabindex="-1">
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
