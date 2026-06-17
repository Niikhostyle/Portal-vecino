@extends('layouts.portal-vecino')

@section('title', 'Mis Solicitudes')
@section('nav_mode', 'full')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-5xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-slate-900">Mis Solicitudes</h1>
            <p class="mt-1 text-sm text-slate-600">Consulta el estado de todas tus solicitudes</p>
        </div>

        <!-- Filtros -->
        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('vecino.mis-solicitudes') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Estado</label>
                    <select name="estado" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                        <option value="">Todos los estados</option>
                        <option value="enviada" {{ request('estado') === 'enviada' ? 'selected' : '' }}>Enviada</option>
                        <option value="en_revision_op" {{ request('estado') === 'en_revision_op' ? 'selected' : '' }}>En Revisión OP</option>
                        <option value="derivada" {{ request('estado') === 'derivada' ? 'selected' : '' }}>Derivada</option>
                        <option value="en_gestion" {{ request('estado') === 'en_gestion' ? 'selected' : '' }}>En Gestión</option>
                        <option value="respondida" {{ request('estado') === 'respondida' ? 'selected' : '' }}>Respondida</option>
                        <option value="rechazada" {{ request('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Buscar por folio</label>
                    <input type="text" name="folio" value="{{ request('folio') }}" placeholder="Ej: CHANCO-2024-000001" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </span>
                    </button>
                </div>
            </form>
        </div>

        @if($solicitudes->count() > 0)
            <div class="space-y-4">
                @foreach($solicitudes as $solicitud)
                    <a href="{{ route('vecino.solicitud.show', $solicitud->id) }}" class="group block overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-slate-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="mb-3 flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base font-semibold text-slate-900 transition-colors group-hover:text-slate-700">
                                            {{ $solicitud->tipo->titulo }}
                                        </h3>
                                        <p class="mt-0.5 text-sm text-slate-600">Folio: {{ $solicitud->folio }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center gap-4">
                                    <div class="flex items-center text-sm text-slate-600">
                                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $solicitud->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $solicitud->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ml-4 flex items-center gap-4">
                                @if($solicitud->estado === 'respondida')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Respondida</span>
                                @elseif($solicitud->estado === 'rechazada')
                                    <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">Rechazada</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">Enviada</span>
                                @endif
                                <svg class="h-5 w-5 text-slate-400 transition-colors group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="mt-6">
                {{ $solicitudes->links() }}
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto max-w-md">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 mx-auto">
                        <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-slate-900">No se encontraron solicitudes</h3>
                    <p class="mb-6 text-sm text-slate-600">Intenta ajustar los filtros de búsqueda o crea una nueva solicitud</p>
                    <a href="{{ route('vecino.solicitudes') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Nueva Solicitud
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
