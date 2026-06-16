@extends('layouts.app')

@section('title', 'Solicitud: ' . $solicitud->folio)

@section('content')
<div class="min-h-screen bg-white">
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Solicitud: {{ $solicitud->folio }}</h1>
            <p class="mt-2 text-base text-slate-600">Gestiona esta solicitud asignada</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información de la Solicitud -->
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Información de la Solicitud</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Folio</dt>
                                <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $solicitud->folio }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Tipo</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->tipo->titulo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Vecino</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->vecino->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Email</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->vecino->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Prioridad</dt>
                                <dd class="mt-1">
                                    @if($solicitud->prioridad === 'urgente')
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">Urgente</span>
                                    @elseif($solicitud->prioridad === 'alta')
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">Alta</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">{{ ucfirst($solicitud->prioridad ?? 'normal') }}</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        @if($solicitud->datos_json)
                            <div class="mt-6 border-t border-slate-200 pt-6">
                                <h3 class="mb-4 text-sm font-semibold text-slate-900">Datos del Trámite</h3>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-3">
                                    @foreach($solicitud->datos_json as $key => $value)
                                        <div>
                                            <dt class="text-sm font-medium text-slate-600">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                            <dd class="mt-1 text-sm text-slate-900">{{ $value }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Adjuntos -->
                @if($solicitud->adjuntos->count() > 0)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
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
                                        <a href="{{ route('funcionario.adjunto.descargar', [$solicitud->id, $adjunto->id]) }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50">
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
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Línea de Tiempo</h2>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($solicitud->eventos as $evento)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 ring-8 ring-white">
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

            <!-- Panel de Acciones -->
            <div class="space-y-6">
                @if(in_array($solicitud->estado, ['respondida', 'rechazada']))
                <!-- Solicitud cerrada: no se puede modificar -->
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-slate-700">Solicitud Cerrada</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-4">
                            <svg class="h-10 w-10 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Esta solicitud ya está {{ $solicitud->estado === 'respondida' ? 'respondida' : 'rechazada' }}.</p>
                                <p class="mt-1 text-sm text-slate-600">No se pueden realizar modificaciones. Solo lectura para auditoría.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Responder -->
                <div class="overflow-hidden rounded-2xl border border-emerald-200 bg-white shadow-sm">
                    <div class="border-b border-emerald-200 bg-emerald-50 px-6 py-4">
                        <h2 class="text-sm font-semibold text-emerald-900">Responder Solicitud</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('funcionario.responder', $solicitud->id) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Respuesta</label>
                                <textarea name="respuesta" rows="5" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors resize-none"></textarea>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Adjuntar Documentos (opcional)</label>
                                <input type="file" name="adjuntos[]" multiple accept=".pdf,.jpg,.jpeg" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors">
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                                <span class="flex items-center justify-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Enviar Respuesta
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Rechazar Solicitud -->
                <div class="overflow-hidden rounded-2xl border border-rose-200 bg-white shadow-sm">
                    <div class="border-b border-rose-200 bg-rose-50 px-6 py-4">
                        <h2 class="text-sm font-semibold text-rose-900">Rechazar Solicitud</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('funcionario.rechazar', $solicitud->id) }}" onsubmit="return confirmSwal(event, { title: 'Rechazar solicitud', text: '¿Está seguro de rechazar esta solicitud? El vecino será notificado.', confirmText: 'Sí, rechazar', confirmColor: '#dc2626' })">
                            @csrf
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-slate-700">Motivo del Rechazo</label>
                                <textarea name="motivo" rows="3" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-0 transition-colors resize-none" placeholder="Indique el motivo del rechazo"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-600 focus:ring-offset-2">
                                <span class="flex items-center justify-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Rechazar Solicitud
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Solicitar Info -->
                <div class="overflow-hidden rounded-2xl border border-amber-200 bg-white shadow-sm">
                    <div class="border-b border-amber-200 bg-amber-50 px-6 py-4">
                        <h2 class="text-sm font-semibold text-amber-900">Solicitar Información</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('funcionario.solicitar-info', $solicitud->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-slate-700">Solicitud de Información Adicional</label>
                                <textarea name="comentario" rows="3" required minlength="10" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-0 transition-colors resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2">
                                Solicitar Info
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
