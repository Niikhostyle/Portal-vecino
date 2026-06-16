@extends('layouts.app')

@section('title', 'Solicitud ' . $solicitud->folio)
@section('header_title', 'Gestión de solicitud')

@php
    $datos = $solicitud->datos_json ?? [];
    $asunto = $datos['asunto'] ?? null;
    $detalle = $datos['detalle'] ?? null;
    $tipoOirs = $datos['tipo_oirs'] ?? null;
    $tipoOirsLabel = match($tipoOirs) {
        'felicitacion' => 'Felicitación',
        'informacion' => 'Información',
        'reclamo' => 'Reclamo',
        'sugerencia' => 'Sugerencia',
        default => $tipoOirs ? ucfirst((string) $tipoOirs) : null,
    };
    $emailVecino = $datos['email'] ?? $datos['mail'] ?? $solicitud->vecino->email;
    $telefono = $datos['telefono'] ?? null;
    $direccion = $datos['direccion'] ?? null;
    $rut = $datos['rut'] ?? null;
    $nombreDatos = $datos['nombre'] ?? $solicitud->vecino->name;

    $camposReservados = ['nombre', 'rut', 'email', 'mail', 'telefono', 'direccion', 'asunto', 'detalle', 'tipo_oirs'];
    $otrosDatos = collect($datos)->except($camposReservados)->filter(fn ($v) => $v !== null && $v !== '');

    $estadoInfo = match($solicitud->estado) {
        'enviada' => ['Enviada', '#dbeafe', '#1e40af', '#eff6ff'],
        'en_revision_op' => ['En revisión', '#fef3c7', '#92400e', '#fffbeb'],
        'derivada' => ['Derivada', '#e0e7ff', '#3730a3', '#eef2ff'],
        'en_gestion' => ['En gestión', '#ffedd5', '#9a3412', '#fff7ed'],
        'respondida' => ['Respondida', '#d1fae5', '#065f46', '#ecfdf5'],
        'rechazada' => ['Rechazada', '#ffe4e6', '#9f1239', '#fff1f2'],
        default => [ucfirst(str_replace('_', ' ', $solicitud->estado)), '#f1f5f9', '#475569', '#f8fafc'],
    };

    $cerrada = in_array($solicitud->estado, ['respondida', 'rechazada']);
@endphp

@section('content')
<div class="min-h-screen" style="background-color: #f8fafc;">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Cabecera --}}
        <div class="mb-6">
            <a href="{{ route('op.bandeja') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-800">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Volver a bandeja
            </a>
            <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ $solicitud->folio }}</h1>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                              style="background-color: {{ $estadoInfo[3] }}; color: {{ $estadoInfo[2] }};">
                            {{ $estadoInfo[0] }}
                        </span>
                        @if($tipoOirsLabel)
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background-color: #e0e7ff; color: #4338ca;">
                                OIRS · {{ $tipoOirsLabel }}
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-slate-600">{{ $solicitud->tipo->titulo }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">Ingresada el {{ $solicitud->created_at->format('d/m/Y') }} a las {{ $solicitud->created_at->format('H:i') }}</p>
                </div>
                @if($solicitud->asignado)
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Asignado a</p>
                        <p class="mt-0.5 font-semibold text-slate-900">{{ $solicitud->asignado->name }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- Columna izquierda: información --}}
            <div class="space-y-5 lg:col-span-7">

                {{-- Contenido de la solicitud --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Contenido de la solicitud</h2>
                    </div>
                    <div class="p-5 sm:p-6">
                        @if($asunto)
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Asunto</p>
                            <p class="mt-1 text-lg font-semibold leading-snug text-slate-900">{{ $asunto }}</p>
                        @endif
                        @if($detalle)
                            <p class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Detalle</p>
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $detalle }}</div>
                        @endif
                        @if(!$asunto && !$detalle && $otrosDatos->isNotEmpty())
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach($otrosDatos as $key => $value)
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                        <dd class="mt-1 text-sm text-slate-900">{{ $value }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        @elseif(!$asunto && !$detalle)
                            <p class="text-sm text-slate-500">Sin detalle adicional registrado.</p>
                        @endif

                        @if($otrosDatos->isNotEmpty() && ($asunto || $detalle))
                            <div class="mt-6 border-t border-slate-200 pt-5">
                                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Datos adicionales</p>
                                <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    @foreach($otrosDatos as $key => $value)
                                        <div class="rounded-lg border border-slate-100 bg-slate-50 px-3 py-2">
                                            <dt class="text-[11px] font-medium text-slate-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                            <dd class="mt-0.5 text-sm font-medium text-slate-900">{{ $value }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Datos del ciudadano --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Datos del ciudadano</h2>
                    </div>
                    <div class="p-5 sm:p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-lg font-bold text-white" style="background-color: #0f172a;">
                                {{ strtoupper(substr($nombreDatos, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-base font-semibold text-slate-900">{{ $nombreDatos }}</p>
                                @if($rut)
                                    <p class="mt-0.5 text-sm text-slate-600">RUT {{ $rut }}</p>
                                @endif
                            </div>
                        </div>
                        <dl class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @if($emailVecino)
                                <div class="flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2.5">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <div class="min-w-0">
                                        <dt class="text-[10px] font-semibold uppercase text-slate-400">Email</dt>
                                        <dd class="truncate text-sm text-slate-900">{{ $emailVecino }}</dd>
                                    </div>
                                </div>
                            @endif
                            @if($telefono)
                                <div class="flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2.5">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <div>
                                        <dt class="text-[10px] font-semibold uppercase text-slate-400">Teléfono</dt>
                                        <dd class="text-sm text-slate-900">{{ $telefono }}</dd>
                                    </div>
                                </div>
                            @endif
                            @if($direccion)
                                <div class="flex items-start gap-2 rounded-lg border border-slate-100 px-3 py-2.5 sm:col-span-2">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <div>
                                        <dt class="text-[10px] font-semibold uppercase text-slate-400">Dirección</dt>
                                        <dd class="text-sm text-slate-900">{{ $direccion }}</dd>
                                    </div>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Respuesta previa --}}
                @if($solicitud->respuesta)
                    <div class="overflow-hidden rounded-2xl border border-emerald-200 bg-white shadow-sm">
                        <div class="border-b border-emerald-200 px-5 py-4 sm:px-6" style="background-color: #ecfdf5;">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-semibold text-emerald-900">Respuesta enviada al vecino</h2>
                                @if($solicitud->fecha_respuesta)
                                    <span class="text-xs text-emerald-700">{{ $solicitud->fecha_respuesta->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-5 sm:p-6">
                            <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-800">{{ $solicitud->respuesta }}</p>
                        </div>
                    </div>
                @endif

                @if($solicitud->estado === 'rechazada' && $solicitud->motivo_rechazo)
                    <div class="overflow-hidden rounded-2xl border border-rose-200 bg-white shadow-sm">
                        <div class="border-b border-rose-200 px-5 py-4 sm:px-6" style="background-color: #fff1f2;">
                            <h2 class="text-sm font-semibold text-rose-900">Motivo del rechazo</h2>
                        </div>
                        <div class="p-5 sm:p-6">
                            <p class="text-sm text-slate-800">{{ $solicitud->motivo_rechazo }}</p>
                        </div>
                    </div>
                @endif

                {{-- Adjuntos --}}
                @if($solicitud->adjuntos->count() > 0)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Archivos adjuntos ({{ $solicitud->adjuntos->count() }})</h2>
                        </div>
                        <ul class="divide-y divide-slate-100">
                            @foreach($solicitud->adjuntos as $adjunto)
                                <li class="flex items-center gap-3 px-5 py-3.5 sm:px-6">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg" style="background-color: #f1f5f9;">
                                        <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-slate-900">{{ $adjunto->filename }}</p>
                                        <p class="text-xs text-slate-500">{{ number_format($adjunto->size / 1024, 1) }} KB</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Historial --}}
                @if($solicitud->eventos->count() > 0)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Historial</h2>
                        </div>
                        <ul class="divide-y divide-slate-100 px-5 py-2 sm:px-6">
                            @foreach($solicitud->eventos->sortByDesc('created_at')->take(8) as $evento)
                                <li class="flex gap-3 py-3">
                                    <div class="mt-1 h-2 w-2 shrink-0 rounded-full" style="background-color: #94a3b8;"></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900">{{ str_replace('_', ' ', ucfirst($evento->evento)) }}</p>
                                        <p class="text-xs text-slate-500">
                                            {{ optional($evento->actor)->name ?? 'Sistema' }} · {{ $evento->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        @if($evento->comentario)
                                            <p class="mt-1 text-xs text-slate-600">{{ $evento->comentario }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Columna derecha: acciones --}}
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-6">
                    @if($cerrada)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="px-5 py-8 text-center sm:px-6">
                                <span class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full" style="background-color: {{ $estadoInfo[3] }};">
                                    @if($solicitud->estado === 'respondida')
                                        <svg class="h-7 w-7" style="color: {{ $estadoInfo[2] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="h-7 w-7" style="color: {{ $estadoInfo[2] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                </span>
                                <h3 class="mt-4 text-lg font-semibold text-slate-900">Solicitud {{ $estadoInfo[0] }}</h3>
                                <p class="mt-2 text-sm text-slate-600">No hay acciones disponibles. La solicitud está cerrada.</p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" x-data="{ tab: 'responder' }">
                            <div class="border-b border-slate-200 px-4 pt-4 sm:px-5">
                                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-slate-500">Gestionar solicitud</h2>
                                <div class="flex gap-1 rounded-xl p-1" style="background-color: #f1f5f9;">
                                    <button type="button" @click="tab = 'responder'"
                                            class="flex-1 rounded-lg px-3 py-2 text-xs font-semibold transition-colors"
                                            :style="tab === 'responder' ? 'background-color:#fff;color:#047857;box-shadow:0 1px 2px rgba(0,0,0,.06)' : 'color:#64748b'">
                                        Responder
                                    </button>
                                    <button type="button" @click="tab = 'derivar'"
                                            class="flex-1 rounded-lg px-3 py-2 text-xs font-semibold transition-colors"
                                            :style="tab === 'derivar' ? 'background-color:#fff;color:#1d4ed8;box-shadow:0 1px 2px rgba(0,0,0,.06)' : 'color:#64748b'">
                                        Derivar
                                    </button>
                                    <button type="button" @click="tab = 'rechazar'"
                                            class="flex-1 rounded-lg px-3 py-2 text-xs font-semibold transition-colors"
                                            :style="tab === 'rechazar' ? 'background-color:#fff;color:#be123c;box-shadow:0 1px 2px rgba(0,0,0,.06)' : 'color:#64748b'">
                                        Rechazar
                                    </button>
                                </div>
                            </div>

                            {{-- Tab: Responder --}}
                            <div x-show="tab === 'responder'" class="p-5 sm:p-6">
                                <p class="mb-4 text-sm text-slate-600">Envíe una respuesta oficial al vecino. Se notificará por correo electrónico.</p>
                                <form method="POST" action="{{ route('op.solicitud.responder', $solicitud->id) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Respuesta al vecino</label>
                                        <textarea name="respuesta" rows="6" required minlength="10"
                                                  placeholder="Redacte la respuesta oficial..."
                                                  class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Documentos adjuntos <span class="font-normal text-slate-400">(opcional)</span></label>
                                        <input type="file" name="adjuntos[]" multiple accept=".pdf,.jpg,.jpeg"
                                               class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-white file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700">
                                        <p class="mt-1 text-xs text-slate-400">PDF o JPG, máx. 5 MB c/u</p>
                                    </div>
                                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90" style="background-color: #059669;">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Enviar respuesta y cerrar
                                    </button>
                                </form>
                            </div>

                            {{-- Tab: Derivar --}}
                            <div x-show="tab === 'derivar'" x-cloak class="p-5 sm:p-6">
                                <p class="mb-4 text-sm text-slate-600">Asigne la solicitud a un funcionario para su gestión interna.</p>
                                <form method="POST" action="{{ route('op.solicitud.derivar', $solicitud->id) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Funcionario responsable</label>
                                        <select name="funcionario_id" required class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                            <option value="">Seleccione funcionario...</option>
                                            @foreach($funcionarios as $funcionario)
                                                <option value="{{ $funcionario->id }}">{{ $funcionario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Prioridad</label>
                                        <select name="prioridad" required class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                            <option value="baja">Baja</option>
                                            <option value="normal" selected>Normal</option>
                                            <option value="alta">Alta</option>
                                            <option value="urgente">Urgente</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Comentario interno <span class="font-normal text-slate-400">(opcional)</span></label>
                                        <textarea name="comentario" rows="3" placeholder="Instrucciones para el funcionario..."
                                                  class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 resize-none"></textarea>
                                    </div>
                                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90" style="background-color: #1e293b;">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        Derivar solicitud
                                    </button>
                                </form>
                            </div>

                            {{-- Tab: Rechazar --}}
                            <div x-show="tab === 'rechazar'" x-cloak class="p-5 sm:p-6">
                                <p class="mb-4 text-sm text-slate-600">Rechace la solicitud indicando el motivo. El vecino podrá ver el motivo en su portal.</p>
                                <form method="POST" action="{{ route('op.solicitud.rechazar', $solicitud->id) }}"
                                      onsubmit="return confirmSwal(event, { title: 'Rechazar solicitud', text: '¿Está seguro de rechazar esta solicitud?', confirmText: 'Sí, rechazar', confirmColor: '#dc2626' })"
                                      class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Motivo del rechazo</label>
                                        <textarea name="motivo" rows="5" required minlength="10"
                                                  placeholder="Indique el motivo del rechazo..."
                                                  class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 resize-none"></textarea>
                                    </div>
                                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90" style="background-color: #e11d48;">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Rechazar solicitud
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
