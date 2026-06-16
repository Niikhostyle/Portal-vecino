@extends('layouts.app')

@section('title', 'Revisar Solicitud: ' . $solicitud->folio)

@section('content')
<div class="min-h-screen bg-white">
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Revisar Solicitud: {{ $solicitud->folio }}</h1>
            <p class="mt-2 text-base text-slate-600">Revisa y gestiona esta solicitud</p>
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
                                <dt class="text-sm font-medium text-slate-600">Fecha</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->created_at->format('d/m/Y H:i') }}</dd>
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

                        @if($solicitud->respuesta)
                            <div class="mt-6 border-t border-slate-200 pt-6">
                                <h3 class="mb-4 text-sm font-semibold text-emerald-900">Respuesta al vecino</h3>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-slate-600">Respuesta</dt>
                                        <dd class="mt-1 whitespace-pre-wrap rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-slate-900">{{ $solicitud->respuesta }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-600">Fecha de respuesta</dt>
                                        <dd class="mt-1 text-sm text-slate-900">{{ $solicitud->fecha_respuesta ? $solicitud->fecha_respuesta->format('d/m/Y H:i') : '-' }}</dd>
                                    </div>
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
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Panel de Acciones -->
            <div class="space-y-6">
                @if(in_array($solicitud->estado, ['respondida', 'rechazada']))
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-slate-700">Solicitud cerrada</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-600">
                            Esta solicitud ya está <strong>{{ $solicitud->estado === 'respondida' ? 'respondida' : 'rechazada' }}</strong>.
                            No se pueden realizar más acciones.
                        </p>
                        @if($solicitud->estado === 'rechazada' && $solicitud->motivo_rechazo)
                            <p class="mt-3 text-sm font-medium text-rose-800">Motivo: {{ $solicitud->motivo_rechazo }}</p>
                        @endif
                    </div>
                </div>
                @else
                <!-- Responder directamente -->
                <div class="overflow-hidden rounded-2xl border border-emerald-200 bg-white shadow-sm">
                    <div class="border-b border-emerald-200 bg-emerald-50 px-6 py-4">
                        <h2 class="text-sm font-semibold text-emerald-900">Responder al vecino</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('op.solicitud.responder', $solicitud->id) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Respuesta</label>
                                <textarea name="respuesta" rows="5" required minlength="10" placeholder="Escriba la respuesta que verá el vecino..."
                                          class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors resize-none"></textarea>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Adjuntar documentos (opcional)</label>
                                <input type="file" name="adjuntos[]" multiple accept=".pdf,.jpg,.jpeg"
                                       class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors">
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                                <span class="flex items-center justify-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Enviar respuesta
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Derivar -->
                <div class="overflow-hidden rounded-2xl border border-blue-200 bg-white shadow-sm">
                    <div class="border-b border-blue-200 bg-blue-50 px-6 py-4">
                        <h2 class="text-sm font-semibold text-blue-900">Derivar a Funcionario</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('op.solicitud.derivar', $solicitud->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Funcionario</label>
                                <select name="funcionario_id" required class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors">
                                    <option value="">Seleccione...</option>
                                    @foreach($funcionarios as $funcionario)
                                        <option value="{{ $funcionario->id }}">{{ $funcionario->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Prioridad</label>
                                <select name="prioridad" required class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors">
                                    <option value="normal">Normal</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                    <option value="baja">Baja</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Comentario Interno</label>
                                <textarea name="comentario" rows="3" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                                <span class="flex items-center justify-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                    Derivar
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Rechazar -->
                <div class="overflow-hidden rounded-2xl border border-rose-200 bg-white shadow-sm">
                    <div class="border-b border-rose-200 bg-rose-50 px-6 py-4">
                        <h2 class="text-sm font-semibold text-rose-900">Rechazar Solicitud</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('op.solicitud.rechazar', $solicitud->id) }}" onsubmit="return confirmSwal(event, { title: 'Rechazar solicitud', text: '¿Está seguro de rechazar esta solicitud?', confirmText: 'Sí, rechazar', confirmColor: '#dc2626' })">
                            @csrf
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-slate-700">Motivo del Rechazo</label>
                                <textarea name="motivo" rows="3" required minlength="10" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-0 transition-colors resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-600 focus:ring-offset-2">
                                <span class="flex items-center justify-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Rechazar
                                </span>
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
