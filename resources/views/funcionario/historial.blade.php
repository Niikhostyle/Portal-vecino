@extends('layouts.app')

@section('title', 'Historial de Solicitudes - Auditoría')
@section('header_title', 'Historial / Auditoría')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-6xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-slate-900">Historial de Solicitudes</h1>
            <p class="mt-1 text-sm text-slate-600">Registro completo para auditoría interna. Todas las solicitudes asignadas a ti.</p>
        </div>

        <!-- Filtros -->
        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('funcionario.historial') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Estado</label>
                    <select name="estado" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                        <option value="">Todos</option>
                        <option value="derivada" {{ request('estado') === 'derivada' ? 'selected' : '' }}>Derivada</option>
                        <option value="en_gestion" {{ request('estado') === 'en_gestion' ? 'selected' : '' }}>En Gestión</option>
                        <option value="respondida" {{ request('estado') === 'respondida' ? 'selected' : '' }}>Respondida</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Folio</label>
                    <input type="text" name="folio" value="{{ request('folio') }}" placeholder="Buscar por folio..." class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </span>
                    </button>
                    <a href="{{ route('funcionario.historial') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        @if($solicitudes->count() > 0)
            <!-- Table Section -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Registro de Auditoría</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $solicitudes->total() }} solicitudes encontradas</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Vecino</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha Asignación</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha Respuesta</th>
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
                                        <div class="text-sm text-slate-600">{{ $solicitud->tipo->titulo }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900">{{ $solicitud->vecino->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($solicitud->estado === 'respondida')
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Respondida</span>
                                        @elseif(in_array($solicitud->estado, ['derivada', 'en_gestion']))
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ $solicitud->fecha_respuesta ? $solicitud->fecha_respuesta->format('d/m/Y H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('funcionario.solicitud.show', $solicitud->id) }}" class="inline-flex items-center rounded-lg border border-blue-600 bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-blue-700">
                                                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $solicitudes->withQueryString()->links() }}
                </div>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto mb-4 h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-4 text-sm font-medium text-slate-900">No hay registros en el historial</p>
                <p class="mt-2 text-sm text-slate-600">Las solicitudes asignadas aparecerán aquí para auditoría</p>
                <a href="{{ route('dashboard') }}" class="mt-6 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                    Volver al Dashboard
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
