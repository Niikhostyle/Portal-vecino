@extends('layouts.app')

@section('title', 'Dashboard Funcionario')
@section('header_title', 'Panel de control')

@section('content')
<div class="min-h-screen bg-white">
    <div class="mx-auto max-w-7xl px-4 pt-6 pb-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <p class="text-sm text-slate-600">Vista general de solicitudes asignadas y auditoría interna</p>
        </div>

        <!-- Stats Grid -->
        <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Total Asignadas</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_asignadas']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Pendientes</p>
                        <p class="text-3xl font-bold text-amber-600">{{ number_format($stats['pendientes']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Respondidas</p>
                        <p class="text-3xl font-bold text-emerald-600">{{ number_format($stats['respondidas']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">En Gestión</p>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['en_gestion']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <a href="{{ route('funcionario.historial') }}" class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-blue-200">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Historial</p>
                        <p class="text-lg font-bold text-slate-900 group-hover:text-blue-600">Ver auditoría →</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 group-hover:bg-blue-50">
                        <svg class="h-6 w-6 text-slate-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Alert Banner -->
        @if($stats['pendientes'] > 0)
            <div class="mb-8 rounded-xl border border-amber-200 bg-amber-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm font-medium text-amber-900">
                            Tienes <span class="font-semibold">{{ $stats['pendientes'] }}</span> solicitudes pendientes
                        </p>
                    </div>
                    <a href="{{ route('funcionario.asignadas') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                        Ver Solicitudes
                    </a>
                </div>
            </div>
        @else
            <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-emerald-900">No tienes solicitudes pendientes</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Solicitudes Pendientes -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Solicitudes Pendientes</h2>
                        <p class="mt-1 text-sm text-slate-600">Requieren tu atención</p>
                    </div>
                    <a href="{{ route('funcionario.asignadas') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Ver todas →</a>
                </div>
                <div class="overflow-x-auto">
                    @if($pendientes->count() > 0)
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Folio</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($pendientes as $solicitud)
                                    <tr class="transition-colors hover:bg-slate-50/50">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-sm text-slate-900">{{ $solicitud->folio }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-600 truncate max-w-[180px]">{{ $solicitud->tipo->titulo }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('funcionario.solicitud.show', $solicitud->id) }}" class="inline-flex items-center rounded-lg border border-blue-600 bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-blue-700">
                                                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto mb-4 h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-slate-600">No hay solicitudes pendientes</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Solicitudes Respondidas -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Solicitudes Respondidas</h2>
                        <p class="mt-1 text-sm text-slate-600">Últimas respuestas enviadas</p>
                    </div>
                    <a href="{{ route('funcionario.historial') }}?estado=respondida" class="text-sm font-medium text-blue-600 hover:text-blue-700">Ver historial →</a>
                </div>
                <div class="overflow-x-auto">
                    @if($solicitudes_respondidas->count() > 0)
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Folio</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Vecino</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha Respuesta</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($solicitudes_respondidas as $solicitud)
                                    <tr class="transition-colors hover:bg-slate-50/50">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-sm text-slate-900">{{ $solicitud->folio }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-600 truncate max-w-[150px]">{{ $solicitud->vecino->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-600">{{ $solicitud->fecha_respuesta ? $solicitud->fecha_respuesta->format('d/m/Y H:i') : '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('funcionario.solicitud.show', $solicitud->id) }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto mb-4 h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-sm text-slate-600">Aún no has respondido solicitudes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historial Reciente (Auditoría) -->
        <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50/50 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Historial Reciente (Auditoría)</h2>
                    <p class="mt-1 text-sm text-slate-600">Registro de todas las solicitudes asignadas para auditoría interna</p>
                </div>
                <a href="{{ route('funcionario.historial') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                    Ver historial completo
                </a>
            </div>
            <div class="overflow-x-auto">
                @if($historial_reciente->count() > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Vecino</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($historial_reciente as $solicitud)
                                <tr class="transition-colors hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-sm text-slate-900">{{ $solicitud->folio }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600 truncate max-w-[200px]">{{ $solicitud->tipo->titulo }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900 truncate max-w-[150px]">{{ $solicitud->vecino->name }}</div>
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
                                        <div class="text-sm text-slate-600">{{ $solicitud->updated_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('funcionario.solicitud.show', $solicitud->id) }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto mb-4 h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-slate-600">No hay registros en el historial</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
