<!DOCTYPE html>
<html lang="es" class="m-0 p-0 w-full overflow-x-hidden" style="margin: 0; padding: 0; width: 100%; overflow-x: hidden;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Ciudadano') - {{ config('app.name', 'Municipalidad') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@24,400,0&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        }).then((result) => {
            if (result.isConfirmed && event && event.target) event.target.submit();
        });
        return false;
    };
    </script>
    @stack('styles')
</head>
<body class="font-display text-slate-900 min-h-screen flex flex-col relative hero-bg m-0 p-0 w-full overflow-x-hidden">
    <header class="w-full z-20 absolute top-0 left-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('img/logo1.png') }}" alt="Municipalidad de Chanco" class="h-16 w-auto object-contain drop-shadow-md" />
                    
                </a>
                <!--<nav class="hidden md:flex space-x-8">
                    <a class="text-sm font-semibold text-white hover:text-white/80 transition-colors drop-shadow-sm" href="{{ url('/') }}">Inicio</a>
                    <a class="text-sm font-semibold text-white hover:text-white/80 transition-colors drop-shadow-sm" href="#">Turismo</a>
                    <a class="text-sm font-semibold text-white hover:text-white/80 transition-colors drop-shadow-sm" href="#">Transparencia</a>
                </nav>
                <div class="flex items-center gap-4">
                    <a href="chanco.cl" class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-4 py-2 rounded-lg text-sm font-bold border border-white/30 transition-all">
                        Volver a Chanco.cl
                    </a>
                </div>-->
            </div>
        </div>
    </header>

    <main class="flex-grow flex flex-col items-center justify-center pt-24 pb-12 px-4 relative z-10">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Columna izquierda: bienvenida --}}
            <div class="text-white space-y-8">
                <div>
                    
                    <h1 class="font-serif-heading text-4xl md:text-5xl lg:text-6xl font-bold leading-tight hero-text-outline">
                        Conectando a <br>nuestra comunidad.
                    </h1>
                    <p class="mt-6 text-lg text-white/90 max-w-lg leading-relaxed hero-text-outline">
                        Acceda a trámites municipales, beneficios sociales y más, diseñado para servir a nuestros vecinos.
                    </p>
                </div>
                 <!--<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a class="group flex items-center gap-4 p-4 glass-panel rounded-xl hover:bg-white/90 transition-all text-slate-900" href="#">
                        <div class="bg-chanco-primary/10 p-3 rounded-lg group-hover:bg-chanco-primary transition-colors">
                            <span class="material-symbols-outlined text-chanco-primary group-hover:text-white">info</span>
                        </div>
                       <div>                            <span class="block font-bold text-sm">Información Pública</span>
                            <span class="text-xs opacity-70">Transparencia y actas</span>
                        </div>
                    </a>
                    <a class="group flex items-center gap-4 p-4 glass-panel rounded-xl hover:bg-white/90 transition-all text-slate-900" href="#">
                        <div class="bg-green-500/10 p-3 rounded-lg group-hover:bg-green-500 transition-colors">
                            <span class="material-symbols-outlined text-green-600 group-hover:text-white">house</span>
                        </div>
                        <div>
                            <span class="block font-bold text-sm">Nuevos Residentes</span>
                            <span class="text-xs opacity-70">Guía de integración</span>
                        </div>
                    </a>
                </div>-->
            </div>

            {{-- Columna derecha: card de login (yield) --}}
            <div class="w-full max-w-md mx-auto lg:ml-auto">
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="w-full py-6 relative z-10">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 text-white/70 text-xs font-medium">
                <span class="material-symbols-outlined text-sm text-green-400">verified_user</span>
                <span>Sitio Oficial de la Ilustre Municipalidad de Chanco</span>
            </div>
            <div class="flex gap-6 text-xs text-white/60">
                <span class="hover:text-white transition-colors" >Privacidad</span>
                <span class="hover:text-white transition-colors" >Departamento de Informática</span>
                <span class="text-white/60">© {{ date('Y') }} Chanco Digital</span>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
