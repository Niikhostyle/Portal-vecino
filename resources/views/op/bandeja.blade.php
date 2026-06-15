@extends('layouts.app')

@section('title', 'Bandeja de Entrada - OP')
@section('header_title', 'Bandeja de Entrada')

@push('styles')
<style>
/* Forzar visibilidad del botón Revisar (evitar conflicto con estilos globales) */
a.btn-revisar {
    background-color: #2563eb !important;
    color: #ffffff !important;
    border-color: #2563eb !important;
}
a.btn-revisar:hover {
    background-color: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
    color: #ffffff !important;
}
</style>
@endpush

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-5xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-slate-900">Bandeja de Entrada</h1>
            <p class="mt-1 text-sm text-slate-600">Gestiona las solicitudes recibidas</p>
        </div>

        <!-- Filtros -->
        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('op.bandeja') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Estado</label>
                    <select name="estado" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                        <option value="">Todos</option>
                        <option value="enviada" {{ request('estado') === 'enviada' ? 'selected' : '' }}>Enviada</option>
                        <option value="en_revision_op" {{ request('estado') === 'en_revision_op' ? 'selected' : '' }}>En Revisión OP</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Sección</label>
                    <select name="seccion" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                        <option value="">Todas</option>
                        <option value="SOCIAL" {{ request('seccion') === 'SOCIAL' ? 'selected' : '' }}>Social</option>
                        <option value="TRÁNSITO Y RENTAS" {{ request('seccion') === 'TRÁNSITO Y RENTAS' ? 'selected' : '' }}>Tránsito y Rentas</option>
                        <option value="MOVILIZACIÓN" {{ request('seccion') === 'MOVILIZACIÓN' ? 'selected' : '' }}>Movilización</option>
                        <option value="RECINTOS MUNICIPALES: SALONES, TEATRO, ESPACIO COMUNITARIO" {{ request('seccion') === 'RECINTOS MUNICIPALES: SALONES, TEATRO, ESPACIO COMUNITARIO' ? 'selected' : '' }}>Recintos Municipales</option>
                        <option value="DEPORTES" {{ request('seccion') === 'DEPORTES' ? 'selected' : '' }}>Deportes</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Folio</label>
                    <input type="text" name="folio" value="{{ request('folio') }}" placeholder="Buscar por folio..." class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
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
            <!-- Table Section -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <!-- Table Header -->
                <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Solicitudes</h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Vecino</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($solicitudes as $solicitud)
                                <tr class="transition-colors hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-sm text-slate-900">{{ $solicitud->folio }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ $solicitud->tipo?->titulo ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900">{{ $solicitud->vecino?->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('op.solicitud.show', $solicitud->id) }}" class="btn-revisar inline-flex items-center rounded-lg border px-3 py-1.5 text-xs font-medium transition-colors">
                                                <svg class="mr-1.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>Revisar</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $solicitudes->links() }}
                </div>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto mb-4 h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="mt-4 text-sm font-medium text-slate-900">No hay solicitudes en la bandeja</p>
                <p class="mt-2 text-sm text-slate-600">Todas las solicitudes han sido procesadas</p>
            </div>
        @endif
    </div>
</div>
@endsection
