@extends('layouts.guest')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login-fallback.css') }}" aria-hidden="true">
@endpush

@section('title', 'Iniciar Sesión')

@section('content')
<div class="glass-panel rounded-2xl shadow-2xl overflow-hidden p-8 border border-white/20">
    <div class="text-center mb-8">
        <h3 class="font-portal-title text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 mb-2">Portal Ciudadano</h3>
        <p class="text-slate-600 text-sm">Inicie sesión para acceder a sus servicios</p>
    </div>

    @if (session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-100 px-4 py-3" role="alert">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ClaveÚnica --}}
    <div class="mb-6">
        <a href="{{ route('auth.claveunica') }}"
           class="btn-cu btn-cu-login w-full flex items-center justify-center font-bold transition-all shadow-lg no-underline"
           aria-label="Iniciar sesión con ClaveÚnica">
            <span class="cl-claveunica shrink-0" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none"><path fill="#FFF" fill-rule="evenodd" d="M11.47,14a.65.65,0,0,1-.21-.54.64.64,0,0,1,.29-.5.68.68,0,0,1,.53-.19.73.73,0,0,1,.49.27.75.75,0,0,1-.08,1,.7.7,0,0,1-.53.19.71.71,0,0,1-.49-.27Zm3.8-8.66A9,9,0,0,1,21,13.57a9,9,0,0,1-18,0A8.78,8.78,0,0,1,8.45,5.32c.37-.09.64-.09.82.36a.76.76,0,0,1-.36,1,7.52,7.52,0,1,0,5.82-.09.66.66,0,0,1-.4-.37.64.64,0,0,1,0-.54.62.62,0,0,1,.37-.39A.64.64,0,0,1,15.27,5.32Zm-7.77,8a4.64,4.64,0,0,1,3.75-4.59V2.33A.91.91,0,0,1,12,1.5h3.31a.75.75,0,0,1,.7.75.78.78,0,0,1-.7.75H12.75V8.76a4.5,4.5,0,0,1,3.75,4.58A4.61,4.61,0,0,1,12,18,4.61,4.61,0,0,1,7.5,13.33Zm7.5.09a3.1,3.1,0,0,0-3-3.12,3.12,3.12,0,0,0,0,6.2A3.09,3.09,0,0,0,15,13.42Z"/></svg>
            </span>
            <span class="texto">Iniciar sesión</span>
        </a>
        <p class="mt-3 text-[10px] text-center text-slate-500 uppercase tracking-widest font-bold">Identidad Digital del Estado</p>
        <a href="https://claveunica.gob.cl/recuperar" target="_blank" rel="noopener noreferrer" class="block text-center mt-2 text-xs text-slate-500 hover:text-chanco-primary transition-colors">Recuperar ClaveÚnica</a>
    </div>

    {{-- Separador --}}
    <div class="relative mb-6">
        <div aria-hidden="true" class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-300"></div>
        </div>
       
    </div>

    

    
    <div class="mt-8 pt-6 border-t border-slate-300/30 text-center">
        <a class="inline-flex items-center gap-2 text-slate-600 hover:text-chanco-primary text-sm font-semibold transition-colors" href="https://claveunica.gob.cl" target="_blank" rel="noopener noreferrer">
            <span class="material-symbols-outlined text-lg">help_center</span>
            ¿Necesita ayuda?
        </a>
    </div>
</div>
@endsection
