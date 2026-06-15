@extends('layouts.app')

@section('title', 'Dashboard Oficina de Partes')
@section('header_title', 'Panel de control')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto max-w-7xl px-4 pt-6 pb-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Panel de control</h1>
            <p class="mt-1 text-sm text-slate-600">Vista general del sistema y gestión de solicitudes</p>
        </div>

        <!-- Stats Grid -->
        <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Solicitudes -->
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Total Solicitudes</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_solicitudes']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pendientes OP -->
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Pendientes OP</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['pendientes_op']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Respondidas -->
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Respondidas</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['respondidas']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rechazadas -->
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Rechazadas</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['rechazadas']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50">
                        <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Banner -->
        @if($stats['pendientes_op'] > 0)
        <div class="mb-8 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-sm font-medium text-amber-900">
                    Tienes <span class="font-semibold">{{ $stats['pendientes_op'] }}</span> solicitudes nuevas esperando revisión.
                </p>
            </div>
        </div>
        @endif

        <!-- Table Section -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <!-- Table Header -->
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Bandeja de Entrada</h2>
                    <p class="mt-1 text-sm text-slate-600">Solicitudes pendientes de revisión</p>
                </div>
                <div class="flex items-center gap-2">
                    <button class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </button>
                    <button class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
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
                        @forelse($nuevas_solicitudes as $solicitud)
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
                                @if(in_array($solicitud->estado, ['enviada', 'en_revision_op']))
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">Enviada</span>
                                @elseif($solicitud->estado === 'derivada')
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">En Revisión</span>
                                @elseif($solicitud->estado === 'respondida')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Respondida</span>
                                @elseif($solicitud->estado === 'rechazada')
                                    <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">Rechazada</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ $solicitud->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('op.solicitud.show', $solicitud->id) }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-800">
                                    Revisar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-sm text-slate-600">No hay solicitudes nuevas pendientes</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                <p class="text-xs text-slate-600">
                    Mostrando {{ $nuevas_solicitudes->count() }} de {{ $stats['total_solicitudes'] }} solicitudes
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-10">
            <h3 class="mb-6 text-lg font-semibold text-slate-900">Acciones Rápidas</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <a href="{{ route('op.bandeja') }}" class="group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-900">Ver Bandeja Completa</p>
                        <p class="text-xs text-slate-600">Todas las solicitudes</p>
                    </div>
                </a>

                <a href="{{ route('recintos.calendario') }}" class="group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-900">Calendario Recintos</p>
                        <p class="text-xs text-slate-600">Gestionar reservas</p>
                    </div>
                </a>

                <a href="{{ route('admin.reportes') }}" class="group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-900">Ver Reportes</p>
                        <p class="text-xs text-slate-600">Analítica de gestión</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
