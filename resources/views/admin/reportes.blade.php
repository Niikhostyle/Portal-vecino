@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto max-w-7xl px-4 pt-8 pb-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Reportes y Estadísticas</h1>
            <p class="mt-1 text-base text-slate-600">Análisis del sistema</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
            <!-- Solicitudes por Estado -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-blue-200 bg-blue-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-blue-900">Solicitudes por Estado</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($stats['por_estado'] as $stat)
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-4">
                                <span class="text-sm font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $stat->estado)) }}</span>
                                <span class="text-lg font-bold text-slate-900">{{ $stat->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Solicitudes por Sección -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-emerald-200 bg-emerald-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-emerald-900">Solicitudes por Sección</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($stats['por_seccion'] as $stat)
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-4">
                                <span class="text-sm font-medium text-slate-700">{{ $stat->seccion }}</span>
                                <span class="text-lg font-bold text-slate-900">{{ $stat->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendientes por Funcionario -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-amber-200 bg-amber-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-amber-900">Pendientes por Funcionario</h2>
            </div>
            <div class="p-6">
                @if($stats['pendientes_por_funcionario']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['pendientes_por_funcionario'] as $stat)
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <span class="text-sm font-medium text-slate-700">{{ $stat->name }}</span>
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ $stat->total }} pendientes</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="py-4 text-center text-sm text-slate-600">No hay solicitudes pendientes asignadas</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
