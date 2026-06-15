@extends('layouts.app')

@section('title', 'Detalle Solicitud: ' . $solicitud->folio)

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-slate-900">Solicitud: {{ $solicitud->folio }}</h1>
            <p class="mt-1 text-sm text-slate-600">Detalle completo de tu solicitud</p>
        </div>

        <div class="space-y-6">
            <!-- Información General -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Información General</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-slate-600">Folio</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $solicitud->folio }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-600">Tipo</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->tipo->titulo }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-600">Estado</dt>
                            <dd class="mt-1">
                                @if($solicitud->estado === 'respondida')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Respondida</span>
                                @elseif($solicitud->estado === 'rechazada')
                                    <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">Rechazada</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">Enviada</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-600">Fecha Creación</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @if($solicitud->asignado)
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Asignado a</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->asignado->name }}</dd>
                            </div>
                        @endif
                        @if($solicitud->motivo_rechazo)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-red-600">Motivo Rechazo</dt>
                                <dd class="mt-1 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">{{ $solicitud->motivo_rechazo }}</dd>
                            </div>
                        @endif
                        @if($solicitud->respuesta)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-600">Respuesta</dt>
                                <dd class="mt-1 whitespace-pre-wrap rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-900">{{ $solicitud->respuesta }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Fecha Respuesta</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->fecha_respuesta ? $solicitud->fecha_respuesta->format('d/m/Y H:i') : '-' }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Adjuntos -->
            @if($solicitud->adjuntos->count() > 0)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Archivos Adjuntos</h2>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-slate-200">
                            @foreach($solicitud->adjuntos as $adjunto)
                                <li class="flex items-center justify-between py-3">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $adjunto->filename }}</p>
                                            <p class="text-xs text-slate-600">{{ number_format($adjunto->size / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('vecino.adjunto.descargar', [$solicitud->id, $adjunto->id]) }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-blue-50 hover:border-blue-200">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Descargar
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Línea de Tiempo -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Línea de Tiempo</h2>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($solicitud->eventos as $index => $evento)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 flex justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm font-medium text-slate-900">{{ ucfirst(str_replace('_', ' ', $evento->evento)) }}</p>
                                                    @if($evento->comentario)
                                                        <p class="mt-1 text-sm text-slate-600">{{ $evento->comentario }}</p>
                                                    @endif
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-slate-600">
                                                    <p>{{ $evento->actor->name }}</p>
                                                    <p>{{ $evento->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
