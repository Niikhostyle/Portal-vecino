@extends('layouts.app')

@section('title', 'Dashboard Vecino')
@section('header_title', 'Dashboard')

@push('styles')
<style>
/* Forzar visibilidad del botón Ver en tabla de solicitudes */
a.btn-ver-solicitud {
    display: inline-flex !important;
    align-items: center !important;
    background-color: #2563eb !important;
    color: #ffffff !important;
    border: 1px solid #2563eb !important;
    text-decoration: none !important;
}
a.btn-ver-solicitud:hover {
    background-color: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
    color: #ffffff !important;
}
a.btn-ver-solicitud span,
a.btn-ver-solicitud svg {
    color: #ffffff !important;
}
a.btn-ver-solicitud svg {
    stroke: #ffffff !important;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-white">
    <div class="mx-auto max-w-7xl px-4 pt-6 pb-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-600">Resumen de tu actividad y solicitudes</p>
            <a href="{{ route('vecino.solicitudes') }}" class="inline-flex shrink-0 items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Crear solicitud
            </a>
        </div>

        <!-- Tarjetas de métricas -->
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Total</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                        <p class="mt-0.5 text-xs text-slate-600">solicitudes</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Pendientes</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">{{ $stats['pendientes'] }}</p>
                        <p class="mt-0.5 text-xs text-slate-600">en trámite</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Respondidas</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $stats['respondida'] }}</p>
                        <p class="mt-0.5 text-xs text-slate-600">resueltas</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Rechazadas</p>
                        <p class="mt-1 text-2xl font-bold text-rose-600">{{ $stats['rechazada'] }}</p>
                        <p class="mt-0.5 text-xs text-slate-600">no aprobadas</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50">
                        <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Gráfico: Solicitudes por estado -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Solicitudes por estado</h2>
                    <p class="mt-0.5 text-sm text-slate-600">Distribución de tus solicitudes</p>
                </div>
                <div class="p-6">
                    @php
                        $estadosChart = [
                            ['label' => 'Enviada', 'count' => $stats['enviada'], 'color' => 'bg-amber-400'],
                            ['label' => 'En revisión', 'count' => $stats['en_revision_op'], 'color' => 'bg-blue-400'],
                            ['label' => 'Derivada / En gestión', 'count' => $stats['derivada'] + $stats['en_gestion'], 'color' => 'bg-slate-400'],
                            ['label' => 'Respondida', 'count' => $stats['respondida'], 'color' => 'bg-emerald-500'],
                            ['label' => 'Rechazada', 'count' => $stats['rechazada'], 'color' => 'bg-rose-400'],
                        ];
                        $maxCount = max(1, $stats['total']);
                    @endphp
                    <div class="space-y-4">
                        @foreach($estadosChart as $item)
                            <div>
                                <div class="mb-1.5 flex justify-between text-sm">
                                    <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
                                    <span class="text-slate-600">{{ $item['count'] }}</span>
                                </div>
                                <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full {{ $item['color'] }} rounded-full transition-all duration-500" style="width: {{ $maxCount > 0 ? round(($item['count'] / $maxCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($stats['total'] === 0)
                        <p class="mt-4 text-center text-sm text-slate-500">Aún no tienes solicitudes. <a href="{{ route('vecino.solicitudes') }}" class="font-medium text-slate-900 underline hover:no-underline">Crear primera solicitud</a></p>
                    @endif
                </div>
            </div>

            <!-- Gráfico: Últimos 6 meses -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Actividad reciente</h2>
                    <p class="mt-0.5 text-sm text-slate-600">Solicitudes por mes</p>
                </div>
                <div class="p-6">
                    @php
                        $maxMeses = max(1, collect($meses)->max('total'));
                    @endphp
                    <div class="flex items-end justify-between gap-2" style="height: 140px;">
                        @foreach($meses as $m)
                            <div class="flex flex-1 flex-col items-center gap-1.5 h-full">
                                <span class="text-xs font-medium text-slate-600">{{ $m['total'] }}</span>
                                <div class="w-full flex-1 min-h-[8px] overflow-hidden rounded-t bg-slate-100 flex flex-col justify-end" title="{{ $m['label'] }}: {{ $m['total'] }}">
                                    <div class="w-full rounded-t bg-slate-900 transition-all duration-500" style="height: {{ $maxMeses > 0 ? max(8, round(($m['total'] / $maxMeses) * 100)) : 8 }}%;"></div>
                                </div>
                                <span class="text-[10px] text-slate-500">{{ $m['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Mis solicitudes recientes -->
        <div class="mt-10 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Mis solicitudes recientes</h2>
                    <p class="mt-0.5 text-sm text-slate-600">Últimas solicitudes que has creado</p>
                </div>
                <a href="{{ route('vecino.mis-solicitudes') }}" class="inline-flex shrink-0 items-center text-sm font-medium text-slate-700 transition-colors hover:text-slate-900">
                    Ver todas
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            @if($mis_solicitudes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Trámite</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($mis_solicitudes as $solicitud)
                                <tr class="transition-colors hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-slate-900">{{ $solicitud->tipo->titulo }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <code class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-800">{{ $solicitud->folio }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if($solicitud->estado === 'respondida')
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Respondida</span>
                                        @elseif($solicitud->estado === 'rechazada')
                                            <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">Rechazada</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end">
                                            <a href="{{ route('vecino.solicitud.show', $solicitud->id) }}" class="btn-ver-solicitud inline-flex items-center rounded-lg border px-3 py-1.5 text-xs font-medium transition-colors shrink-0">
                                                <svg class="mr-1.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>Ver</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="mt-4 text-sm font-medium text-slate-900">Aún no tienes solicitudes</p>
                    <p class="mt-1 text-sm text-slate-600">Crea tu primera solicitud y aparecerá aquí.</p>
                    <a href="{{ route('vecino.solicitudes') }}" class="mt-6 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700">Crear solicitud</a>
                </div>
            @endif
        </div>

        <!-- Accesos rápidos -->
        <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <a href="{{ route('vecino.solicitudes') }}" class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-slate-300 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Crear solicitud</h3>
                    <p class="mt-0.5 text-sm text-slate-600">Inicia un nuevo trámite municipal</p>
                </div>
                <svg class="ml-auto h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            <a href="{{ route('vecino.mis-solicitudes') }}" class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-slate-300 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                    <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Mis solicitudes</h3>
                    <p class="mt-0.5 text-sm text-slate-600">Ver historial y estado de tus trámites</p>
                </div>
                <svg class="ml-auto h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
