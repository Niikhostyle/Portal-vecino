@extends('layouts.portal-vecino')

@section('title', 'Crear Solicitud')
@section('nav_mode', 'full')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-5xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-slate-900">Crear Nueva Solicitud</h1>
            <p class="mt-1 text-sm text-slate-600">Selecciona el tipo de solicitud que deseas realizar</p>
        </div>

        <!-- Grid de categorías -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($tipos_solicitud as $seccion => $tipos)
                <div class="flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all hover:shadow-md hover:border-slate-300">
                    <!-- Header de la categoría -->
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                        <h2 class="text-sm font-semibold text-slate-900">{{ $seccion }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $tipos->count() }} {{ $tipos->count() === 1 ? 'trámite' : 'trámites' }}</p>
                    </div>
                    
                    <!-- Lista de trámites (se expande automáticamente) -->
                    <div class="flex-1 p-3">
                        <div class="space-y-2">
                            @foreach($tipos as $tipo)
                                <a href="{{ route('vecino.iniciar-solicitud', $tipo->id) }}" class="group flex items-start gap-2 rounded-lg border border-slate-200 bg-white p-3 transition-all hover:border-blue-200 hover:bg-blue-50/50">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 transition-colors group-hover:bg-blue-100">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-slate-900 transition-colors group-hover:text-slate-700">
                                            {{ $tipo->titulo }}
                                        </h3>
                                        @if($tipo->descripcion)
                                            <p class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-slate-600">
                                                {{ Str::limit($tipo->descripcion, 60) }}
                                            </p>
                                        @endif
                                    </div>
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400 transition-transform group-hover:translate-x-0.5 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
