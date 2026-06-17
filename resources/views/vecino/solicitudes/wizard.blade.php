@extends('layouts.portal-vecino')

@section('title', 'Crear Solicitud: ' . $tipo->titulo)
@section('nav_mode', 'burger')
@section('header_title', $tipo->titulo)

@push('styles')
<style>
#btnSiguiente {
    background-color: #2563eb !important;
    color: #ffffff !important;
}
#btnSiguiente:hover {
    background-color: #1d4ed8 !important;
}
#btnSiguiente svg {
    color: inherit;
}
</style>
@endpush

@section('content')
<div class="p-6 lg:p-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-900">{{ $tipo->titulo }}</h1>
        <p class="mt-1 text-sm text-neutral-600">{{ $tipo->descripcion }}</p>
    </div>

    <!-- Stepper -->
    <div class="mb-8 bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <nav aria-label="Progreso" class="overflow-x-auto">
            <ol class="flex items-center w-full">
                <li class="flex items-center flex-1 min-w-0" id="step-1-indicator">
                    <div class="flex flex-col items-center shrink-0">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-slate-300 transition-all duration-200" id="step-1-circle">
                            <span class="text-sm font-semibold text-slate-400" id="step-1-text">1</span>
                        </span>
                        <span class="mt-2 text-xs font-medium text-center text-slate-600 whitespace-nowrap" id="step-1-label">Identificación</span>
                    </div>
                    <div class="flex-1 h-0.5 mx-1 min-w-[12px] bg-slate-200 self-start mt-5" id="step-1-line"></div>
                </li>
                <li class="flex items-center flex-1 min-w-0" id="step-2-indicator">
                    <div class="flex flex-col items-center shrink-0">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-slate-300 transition-all duration-200" id="step-2-circle">
                            <span class="text-sm font-semibold text-slate-400" id="step-2-text">2</span>
                        </span>
                        <span class="mt-2 text-xs font-medium text-center text-slate-600 whitespace-nowrap" id="step-2-label">Datos del Trámite</span>
                    </div>
                    <div class="flex-1 h-0.5 mx-1 min-w-[12px] bg-slate-200 self-start mt-5" id="step-2-line"></div>
                </li>
                <li class="flex items-center flex-1 min-w-0" id="step-3-indicator">
                    <div class="flex flex-col items-center shrink-0">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-slate-300 transition-all duration-200" id="step-3-circle">
                            <span class="text-sm font-semibold text-slate-400" id="step-3-text">3</span>
                        </span>
                        <span class="mt-2 text-xs font-medium text-center text-slate-600 whitespace-nowrap" id="step-3-label">Adjuntos</span>
                    </div>
                    <div class="flex-1 h-0.5 mx-1 min-w-[12px] bg-slate-200 self-start mt-5" id="step-3-line"></div>
                </li>
                <li class="flex items-center shrink-0" id="step-4-indicator">
                    <div class="flex flex-col items-center">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-slate-300 transition-all duration-200" id="step-4-circle">
                            <span class="text-sm font-semibold text-slate-400" id="step-4-text">4</span>
                        </span>
                        <span class="mt-2 text-xs font-medium text-center text-slate-600 whitespace-nowrap" id="step-4-label">Confirmación</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    @isset($vecino)
    <div class="mb-6 flex items-center gap-3 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3">
        <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <span class="text-sm font-medium text-blue-900">Creando solicitud en nombre de: <strong>{{ $vecino->name }}</strong></span>
    </div>
    @endisset

    <form id="solicitudForm" novalidate enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        <input type="hidden" name="tipo_id" value="{{ $tipo->id }}">
        @isset($vecino)
        <input type="hidden" name="vecino_id" value="{{ $vecino->id }}">
        @endisset
        
        @php
            $campos = $tipo->campos_requisitos ?? [];
            $usaRequisitos = count($campos) > 0;
            $mostrarCampo = function($key) use ($campos, $usaRequisitos) {
                return !$usaRequisitos || in_array($key, $campos);
            };
            $esRecinto = in_array($tipo->codigo, ['RECINTOS_MUNICIPALES', 'RECINTOS_DEPORTIVOS']) || in_array('recinto', $campos);
            $esRecintoDeportivo = $tipo->codigo === 'RECINTOS_DEPORTIVOS' || ($tipo->usar_horarios_disponibles ?? false);
            $esPatentes = $tipo->codigo === 'PATENTES';
            $esOirs = $tipo->esOirs();
            $ciudadanoIdentidad = ($ciudadano ?? auth()->user())->datosIdentidadClaveUnica();
        @endphp
        <!-- Paso 1: Identificación -->
        <div class="step-content p-6 lg:p-8" id="step1">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Identificación</h2>
                <p class="text-sm text-slate-600">Completa tus datos personales</p>
                @if(!empty($datosPrecargados['telefono']) || !empty($datosPrecargados['direccion']))
                <div class="mt-3 flex items-start gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                    <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Hemos precargado tus datos de solicitudes anteriores. Puedes actualizarlos si lo necesitas.</span>
                </div>
                @endif
            </div>
            <div class="space-y-5">
                {{-- Nombre y RUT: siempre desde Clave Única, no editables --}}
                @if(empty($ciudadanoIdentidad['rut']))
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm text-amber-900">
                        No se encontró RUT asociado a su sesión. Cierre sesión e ingrese nuevamente con <strong>ClaveÚnica</strong> para completar su identificación.
                    </p>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nombre Completo</label>
                    <input type="text" name="datos[nombre]" value="{{ $ciudadanoIdentidad['nombre'] }}" required readonly
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-slate-50 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <p class="mt-1 text-xs text-slate-500">Obtenido desde Clave Única al iniciar sesión</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">RUT</label>
                    <input type="text" name="datos[rut]" value="{{ $ciudadanoIdentidad['rut'] }}" placeholder="12.345.678-9" required readonly
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-slate-50 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <p class="mt-1 text-xs text-slate-500">Obtenido desde Clave Única al iniciar sesión</p>
                </div>
                @if($mostrarCampo('mail') || $mostrarCampo('email') || !$usaRequisitos)
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Correo Electrónico</label>
                    <input type="email" name="datos[email]" value="{{ $datosPrecargados['email'] ?? auth()->user()->email ?? '' }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                @endif
                @if($mostrarCampo('telefono') || !$usaRequisitos)
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Teléfono</label>
                    <input type="tel" name="datos[telefono]" value="{{ $datosPrecargados['telefono'] ?? '' }}" placeholder="+56 9 1234 5678" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                @endif
                @if($mostrarCampo('direccion') || !$usaRequisitos)
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Dirección</label>
                    <input type="text" name="datos[direccion]" value="{{ $datosPrecargados['direccion'] ?? '' }}" placeholder="Calle, número, comuna" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                @endif
            </div>
        </div>

        <!-- Paso 2: Datos específicos (según requisitos o lógica por código) -->
        <div class="step-content hidden p-6 lg:p-8" id="step2">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Datos del Trámite</h2>
                <p class="text-sm text-slate-600">Información específica de tu solicitud</p>
            </div>
            <div class="space-y-5">
                @if($esOirs)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tipo de solicitud OIRS <span class="text-red-500">*</span></label>
                        @php $tipoOirsSel = request('tipo_oirs'); @endphp
                        <select name="datos[tipo_oirs]" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Seleccione...</option>
                            <option value="felicitacion" @selected($tipoOirsSel === 'felicitacion')>Felicitación</option>
                            <option value="informacion" @selected($tipoOirsSel === 'informacion')>Solicitud de Información</option>
                            <option value="reclamo" @selected($tipoOirsSel === 'reclamo')>Reclamo</option>
                            <option value="sugerencia" @selected($tipoOirsSel === 'sugerencia')>Sugerencia</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Asunto <span class="text-red-500">*</span></label>
                        <input type="text" name="datos[asunto]" required maxlength="255" placeholder="Resumen breve de su solicitud"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Detalle de su solicitud <span class="text-red-500">*</span></label>
                        <textarea name="datos[detalle]" rows="6" required placeholder="Describa con el mayor detalle posible su información, reclamo o sugerencia..."
                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"></textarea>
                    </div>
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <p class="text-sm text-blue-900">
                            Este formulario corresponde al canal oficial OIRS de la Municipalidad de Chanco. Su solicitud será registrada y derivada al área correspondiente.
                        </p>
                    </div>
                @elseif($esPatentes)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tipo de Trámite</label>
                        <select name="datos[tipo_tramite]" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Seleccione...</option>
                            <option value="alta">Alta</option>
                            <option value="modificacion">Modificación</option>
                            <option value="consulta">Consulta</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Detalle del Trámite</label>
                        <textarea name="datos[detalle]" rows="4" required placeholder="Describe los detalles de tu trámite..." class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"></textarea>
                    </div>
                @elseif($esRecinto && isset($recintos) && $recintos->count() > 0)
                    <div id="bloqueRecinto">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recinto</label>
                        <select name="datos[recinto_id]" id="recintoSelect" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Seleccione un recinto...</option>
                            @foreach($recintos as $recinto)
                                <option value="{{ $recinto->id }}">{{ $recinto->nombre }} @if($recinto->tipo)({{ ucfirst(str_replace('_', ' ', $recinto->tipo)) }})@endif</option>
                            @endforeach
                        </select>
                        <p id="recintoBloqueadoMsg" class="hidden mt-2 text-xs text-slate-600 flex items-center gap-1">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Recinto seleccionado. Para reservar otro recinto, inicie una nueva solicitud.
                        </p>
                    </div>

                    @if($esRecintoDeportivo)
                    {{-- Recintos deportivos: calendario + selector de horarios --}}
                    <div id="bloqueCalendarioDeportivo" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Seleccione el día</label>
                            <input type="date" id="fechaCalendario" min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div id="contenedorHorarios" class="hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Seleccione el horario disponible</label>
                            <div id="listaHorarios" class="flex flex-wrap gap-2"></div>
                            <p id="sinHorarios" class="hidden text-sm text-amber-700 mt-2">No hay horarios disponibles para esta fecha.</p>
                        </div>
                        <input type="hidden" name="datos[fecha_inicio]" id="fechaInicioHidden" required>
                        <input type="hidden" name="datos[hora_inicio]" id="horaInicioHidden" required>
                        <input type="hidden" name="datos[fecha_fin]" id="fechaFinHidden" required>
                        <input type="hidden" name="datos[hora_fin]" id="horaFinHidden" required>
                    </div>
                    @else
                    {{-- Recintos municipales (salones, teatro): inputs tradicionales --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Inicio</label>
                            <input type="date" name="datos[fecha_inicio]" id="fechaInicio" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Hora Inicio</label>
                            <input type="time" name="datos[hora_inicio]" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Fin</label>
                            <input type="date" name="datos[fecha_fin]" id="fechaFin" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Hora Fin</label>
                            <input type="time" name="datos[hora_fin]" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    @endif

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-amber-900">Los horarios seleccionados quedarán reservados y no estarán disponibles para otros usuarios.</p>
                        </div>
                    </div>
                @elseif($esRecinto && isset($recintos) && $recintos->count() === 0)
                    {{-- Recintos pero sin disponibilidad --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                        <p class="text-sm text-amber-900 font-medium">No hay recintos disponibles para este tipo de solicitud en este momento.</p>
                        <p class="text-sm text-amber-800 mt-2">Por favor, contacte a la municipalidad para más información.</p>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Comentarios (opcional)</label>
                            <textarea name="datos[detalle]" rows="3" placeholder="Indique su consulta o solicitud..." class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white resize-none"></textarea>
                        </div>
                    </div>
                @else
                    {{-- Resto de trámites (CRED_DISCAPACIDAD, TRASLADO_VEHICULO, MOVILIZACION, etc.): campo detalle genérico --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Detalle o Comentarios</label>
                        <textarea name="datos[detalle]" rows="4" placeholder="Describe tu solicitud..." class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"></textarea>
                    </div>
                @endif
            </div>
        </div>

        <!-- Paso 3: Adjuntos -->
        <div class="step-content hidden p-6 lg:p-8" id="step3">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Adjuntos</h2>
                <p class="text-sm text-slate-600">Sube los documentos requeridos</p>
            </div>
            
            @if($tipo->codigo === 'MOVILIZACION')
                <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-amber-900">
                            <strong>Importante:</strong> No adjunte diagnósticos ni antecedentes sensibles. Solo incluya datos mínimos necesarios.
                        </p>
                    </div>
                </div>
            @endif
            
            @if($tipo->requiere_adjuntos)
                @if($tipo->documentos_requeridos && count($tipo->documentos_requeridos))
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-slate-900 mb-3">Documentos requeridos:</p>
                        <p class="text-xs text-slate-600 mb-3">Para cada documento requerido, adjunta su archivo correspondiente.</p>
                    </div>

                    <div class="space-y-4">
                        @foreach($tipo->documentos_requeridos as $idx => $req)
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $req }}</p>
                                            <p class="text-xs text-slate-500">Formatos permitidos: PDF, JPG. Máx. 5MB.</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <input
                                        type="file"
                                        name="adjuntos[{{ $idx }}]"
                                        accept=".pdf,.jpg,.jpeg"
                                        class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-colors"
                                    >
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Adjuntar Archivos
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed border-slate-200 rounded-lg hover:border-slate-400 transition-colors bg-slate-50">
                            <div class="space-y-3 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex flex-col items-center text-sm text-slate-600">
                                    <label class="relative cursor-pointer bg-white rounded-lg px-4 py-2 border border-slate-200 font-medium text-slate-900 hover:bg-slate-50 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 transition-all">
                                        <span>Seleccionar archivos</span>
                                        <input type="file" name="adjuntos[]" multiple accept=".pdf,.jpg,.jpeg" class="sr-only">
                                    </label>
                                    <p class="mt-2 text-xs text-slate-500">PDF, JPG - Máx. 5MB cada uno</p>
                                </div>
                            </div>
                        </div>
                        <div id="fileList" class="mt-4 space-y-2"></div>
                    </div>
                @endif
            @else
                <p class="text-sm text-slate-600">Este trámite no requiere adjuntar documentos. Puede continuar.</p>
            @endif
        </div>

        <!-- Paso 4: Confirmación -->
        <div class="step-content hidden p-6 lg:p-8" id="step4">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Confirmación</h2>
                <p class="text-sm text-slate-600">Revisa la información antes de enviar</p>
            </div>
            <div id="resumenDatos" class="bg-slate-50 rounded-lg p-6 border border-slate-200 space-y-4"></div>
        </div>

        <!-- Botones de navegación -->
        <div class="px-6 py-4 lg:px-8 lg:py-6 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
            <button type="button" id="btnAnterior" onclick="previousStep()" class="hidden w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Anterior
            </button>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto sm:ml-auto">
                <button type="button" id="btnSiguiente" onclick="nextStep()" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Siguiente
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <button type="submit" id="btnEnviar" class="hidden w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Enviar Solicitud
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 4;

// Datos de recintos disponibles (si aplica)
@if(isset($recintos) && $recintos->count() > 0)
const recintos = @json($recintos);
const esRecintoDeportivo = @json($esRecintoDeportivo ?? false);
@else
const recintos = [];
const esRecintoDeportivo = false;
@endif

// Bloquear recinto una vez seleccionado (no permitir cambiar; para otro recinto, nueva solicitud)
function initRecintoLock() {
    const recintoSelect = document.getElementById('recintoSelect');
    const bloqueadoMsg = document.getElementById('recintoBloqueadoMsg');
    if (!recintoSelect || !bloqueadoMsg) return;

    recintoSelect.addEventListener('change', function() {
        if (this.value && !this.disabled) {
            this.disabled = true;
            this.classList.add('bg-slate-100', 'cursor-not-allowed');
            this.removeAttribute('name');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'datos[recinto_id]';
            hidden.value = this.value;
            hidden.id = 'recintoIdHidden';
            this.closest('#bloqueRecinto').appendChild(hidden);
            bloqueadoMsg.classList.remove('hidden');
        }
    });
}

// Calendario y horarios para recintos deportivos
function initCalendarioDeportivo() {
    const bloque = document.getElementById('bloqueCalendarioDeportivo');
    if (!bloque || !esRecintoDeportivo) return;

    const recintoSelect = document.getElementById('recintoSelect');
    const fechaInput = document.getElementById('fechaCalendario');
    const contenedorHorarios = document.getElementById('contenedorHorarios');
    const listaHorarios = document.getElementById('listaHorarios');
    const sinHorarios = document.getElementById('sinHorarios');
    const fechaInicioHidden = document.getElementById('fechaInicioHidden');
    const horaInicioHidden = document.getElementById('horaInicioHidden');
    const fechaFinHidden = document.getElementById('fechaFinHidden');
    const horaFinHidden = document.getElementById('horaFinHidden');

    function cargarHorarios() {
        const recintoId = recintoSelect?.value;
        const fecha = fechaInput?.value;
        if (!recintoId || !fecha) {
            contenedorHorarios.classList.add('hidden');
            return;
        }

        listaHorarios.innerHTML = '<span class="text-sm text-slate-500">Cargando...</span>';
        contenedorHorarios.classList.remove('hidden');
        sinHorarios.classList.add('hidden');

        fetch(`/recintos/${recintoId}/horarios-disponibles?fecha=${fecha}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            listaHorarios.innerHTML = '';
            const disponibles = data.disponibles || [];
            if (disponibles.length === 0) {
                sinHorarios.classList.remove('hidden');
                return;
            }
            disponibles.forEach(slot => {
                if (!slot || typeof slot !== 'string') return;
                const h = parseInt(String(slot).split(':')[0], 10);
                if (isNaN(h) || h < 0 || h > 23) return;
                const slotNorm = `${String(h).padStart(2, '0')}:00`;
                const slotEnd = `${String(h + 1).padStart(2, '0')}:00`;
                const label = `${slotNorm} - ${slotEnd}`;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'px-4 py-2.5 rounded-lg border-2 border-slate-300 bg-white text-sm font-medium text-slate-800 hover:bg-blue-50 hover:border-blue-400 transition-all min-w-[120px]';
                btn.innerHTML = `<span class="slot-label">${label}</span>`;
                btn.dataset.horaInicio = slotNorm;
                btn.dataset.horaFin = slotEnd;
                btn.dataset.label = label;
                btn.addEventListener('click', function() {
                    document.querySelectorAll('#listaHorarios button').forEach(b => {
                        b.classList.remove('border-blue-600', 'bg-blue-600');
                        b.classList.add('border-slate-300', 'bg-white');
                        b.querySelector('.slot-label') && (b.querySelector('.slot-label').style.color = '');
                        b.style.backgroundColor = '';
                        b.style.borderColor = '';
                        b.style.color = '';
                    });
                    this.classList.remove('border-slate-300', 'bg-white');
                    this.classList.add('border-blue-600', 'bg-blue-600');
                    this.style.backgroundColor = '#2563eb';
                    this.style.borderColor = '#2563eb';
                    this.style.color = '#ffffff';
                    const lbl = this.querySelector('.slot-label');
                    if (lbl) lbl.style.color = '#ffffff';
                    fechaInicioHidden.value = fecha;
                    fechaFinHidden.value = fecha;
                    horaInicioHidden.value = this.dataset.horaInicio;
                    horaFinHidden.value = this.dataset.horaFin;
                });
                listaHorarios.appendChild(btn);
            });
        })
        .catch(() => {
            listaHorarios.innerHTML = '<span class="text-sm text-red-600">Error al cargar horarios</span>';
        });
    }

    fechaInput?.addEventListener('change', cargarHorarios);
    recintoSelect?.addEventListener('change', () => {
        if (contenedorHorarios) contenedorHorarios.classList.add('hidden');
        if (fechaInicioHidden) fechaInicioHidden.value = '';
        if (horaInicioHidden) horaInicioHidden.value = '';
        if (fechaFinHidden) fechaFinHidden.value = '';
        if (horaFinHidden) horaFinHidden.value = '';
        if (fechaInput?.value && recintoSelect?.value) cargarHorarios();
    });
}

function updateStepper(step) {
    for (let i = 1; i <= totalSteps; i++) {
        const circle = document.getElementById(`step-${i}-circle`);
        const text = document.getElementById(`step-${i}-text`);
        const label = document.getElementById(`step-${i}-label`);
        const line = document.getElementById(`step-${i}-line`);
        
        if (i < step) {
            // Completado
            circle.className = 'flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 border-2 border-emerald-600 transition-all duration-200';
            text.innerHTML = '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            label.className = 'mt-2 text-xs font-medium text-center text-slate-600 whitespace-nowrap';
            if (line) line.className = 'flex-1 h-0.5 mx-1 min-w-[12px] bg-emerald-600 self-start mt-5 transition-all duration-200';
        } else if (i === step) {
            // Activo
            circle.className = 'flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 border-2 border-blue-600 transition-all duration-200';
            text.innerHTML = `<span class="text-sm font-semibold text-white">${i}</span>`;
            label.className = 'mt-2 text-xs font-medium text-center text-slate-900 font-semibold whitespace-nowrap';
            if (line) line.className = 'flex-1 h-0.5 mx-1 min-w-[12px] bg-slate-200 self-start mt-5 transition-all duration-200';
        } else {
            // Pendiente
            circle.className = 'flex h-10 w-10 items-center justify-center rounded-full border-2 border-slate-300 bg-white transition-all duration-200';
            text.innerHTML = `<span class="text-sm font-semibold text-slate-400">${i}</span>`;
            label.className = 'mt-2 text-xs font-medium text-center text-slate-400 whitespace-nowrap';
            if (line) line.className = 'flex-1 h-0.5 mx-1 min-w-[12px] bg-slate-200 self-start mt-5 transition-all duration-200';
        }
    }
}

function showStep(step) {
    // Ocultar todos los pasos
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`step${step}`).classList.remove('hidden');
    
    // Obtener referencias a los botones
    const btnAnterior = document.getElementById('btnAnterior');
    const btnSiguiente = document.getElementById('btnSiguiente');
    const btnEnviar = document.getElementById('btnEnviar');
    
    // Botón Anterior: solo visible si NO estamos en el paso 1
    if (btnAnterior) {
        if (step === 1) {
            btnAnterior.classList.add('hidden');
        } else {
            btnAnterior.classList.remove('hidden');
        }
    }
    
    // Botón Siguiente: solo visible si NO estamos en el último paso
    if (btnSiguiente) {
        if (step === totalSteps) {
            // Ocultar en el paso 4 - usar !important para sobrescribir clases de Tailwind
            btnSiguiente.classList.add('hidden');
            btnSiguiente.style.setProperty('display', 'none', 'important'); // Forzar ocultación con !important
        } else {
            // Mostrar en pasos anteriores
            btnSiguiente.classList.remove('hidden');
            btnSiguiente.style.removeProperty('display'); // Remover display para que Tailwind funcione
        }
    }
    
    // Botón Enviar: SOLO visible en el último paso (confirmación) - FORZAR OCULTO EN OTROS PASOS
    if (btnEnviar) {
        if (step === totalSteps) {
            // Solo mostrar en el paso 4
            btnEnviar.classList.remove('hidden');
            btnEnviar.style.removeProperty('display'); // Remover display para que Tailwind funcione
        } else {
            // Forzar oculto en todos los demás pasos
            btnEnviar.classList.add('hidden');
            btnEnviar.style.setProperty('display', 'none', 'important'); // Forzar ocultación con !important
        }
    }
    
    if (step === totalSteps) {
        generarResumen();
    }
    
    updateStepper(step);
    currentStep = step;
}

function validateStep(step) {
    const stepEl = document.getElementById(`step${step}`);
    const inputs = stepEl.querySelectorAll('input[required]:not([disabled]), select[required]:not([disabled]), textarea[required]:not([disabled])');
    let valid = true;
    let firstMessage = '';
    
    inputs.forEach(input => {
        const value = String(input.value || '').trim();
        const isEmpty = input.type === 'file'
            ? !input.files || input.files.length === 0
            : !value;

        input.classList.remove('border-red-300', 'ring-2', 'ring-red-200');

        if (isEmpty) {
            input.classList.add('border-red-300', 'ring-2', 'ring-red-200');
            valid = false;
            if (!firstMessage) {
                firstMessage = 'Complete todos los campos obligatorios del paso ' + step + '.';
            }
            return;
        }

        const minLen = input.getAttribute('minlength');
        if (minLen && value.length < parseInt(minLen, 10)) {
            input.classList.add('border-red-300', 'ring-2', 'ring-red-200');
            valid = false;
            if (!firstMessage) {
                const label = input.closest('div')?.querySelector('label')?.textContent?.replace('*', '').trim() || 'Este campo';
                firstMessage = label + ' debe tener al menos ' + minLen + ' caracteres.';
            }
        }

        const maxLen = input.getAttribute('maxlength');
        if (maxLen && value.length > parseInt(maxLen, 10)) {
            input.classList.add('border-red-300', 'ring-2', 'ring-red-200');
            valid = false;
            if (!firstMessage) {
                const label = input.closest('div')?.querySelector('label')?.textContent?.replace('*', '').trim() || 'Este campo';
                firstMessage = label + ' no puede superar ' + maxLen + ' caracteres.';
            }
        }
    });
    
    validateStep.lastMessage = firstMessage;
    return valid;
}

function validateAllSteps() {
    for (let step = 1; step < totalSteps; step++) {
        if (!validateStep(step)) {
            showStep(step);
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: validateStep.lastMessage || ('Revise el paso ' + step + ' antes de enviar la solicitud.'),
            });
            return false;
        }
    }
    return true;
}

function nextStep() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
}

function previousStep() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function generarResumen() {
    const formData = new FormData(document.getElementById('solicitudForm'));
    const datos = {};
    
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('datos[')) {
            const field = key.match(/datos\[(.*?)\]/)[1];
            datos[field] = value;
        }
    }
    
    let html = '<div class="space-y-3">';
    for (let [key, value] of Object.entries(datos)) {
        const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        html += `<div class="flex justify-between items-center py-2 border-b border-slate-200 last:border-0">
            <span class="text-sm font-medium text-slate-700">${label}:</span>
            <span class="text-sm text-slate-900 text-right">${value || '-'}</span>
        </div>`;
    }
    html += '</div>';
    
    document.getElementById('resumenDatos').innerHTML = html;
}

// Manejar envío del formulario
document.getElementById('solicitudForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!validateAllSteps()) {
        return;
    }

    const btnEnviar = document.getElementById('btnEnviar');
    if (btnEnviar) {
        btnEnviar.disabled = true;
        btnEnviar.classList.add('opacity-60', 'cursor-not-allowed');
    }
    
    const formData = new FormData(e.target);
    const tipoId = {{ $tipo->id }};
    const crearPorStaff = {{ isset($vecino) ? 'true' : 'false' }};
    const storeUrl = crearPorStaff ? `/staff/solicitud/${tipoId}` : `/vecino/solicitud/${tipoId}`;
    
    try {
        const response = await fetch(storeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        let data = {};
        try {
            data = await response.json();
        } catch (parseError) {
            throw new Error('Respuesta inválida del servidor (código ' + response.status + ')');
        }
        
        if (response.ok && data.success) {
            const folio = data.folio || '';
            const fecha = new Date().toLocaleString('es-CL');
            Swal.fire({
                icon: 'success',
                title: 'Solicitud Creada Exitosamente',
                html: `<p class="text-slate-600 mb-4">La solicitud ha sido ingresada correctamente.</p>
                    <div class="bg-slate-50 rounded-lg p-4 mb-4 text-left space-y-2 border border-slate-200">
                        <div class="flex justify-between"><span class="font-medium text-slate-700">Folio:</span><span class="font-semibold">${folio}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-slate-700">Fecha:</span><span>${fecha}</span></div>
                    </div>
                    <p class="text-sm text-slate-500">Si registró un correo electrónico, recibirá una confirmación de seguimiento.</p>`,
                showCancelButton: true,
                confirmButtonText: crearPorStaff ? 'Ir a Bandeja' : 'Ver Mis Solicitudes',
                cancelButtonText: 'Crear Otra Solicitud',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = crearPorStaff ? '{{ route("op.bandeja") }}' : '{{ route("vecino.mis-solicitudes") }}';
                } else {
                    window.location.href = crearPorStaff ? '{{ route("staff.crear-solicitud") }}' : '{{ route("vecino.solicitudes") }}';
                }
            });
        } else {
            let errorMsg = data.message || 'Error al enviar la solicitud';
            if (data.errors) {
                const firstError = Object.values(data.errors).flat()[0];
                if (firstError) errorMsg = firstError;
            }
            Swal.fire({ icon: 'error', title: 'Error', text: errorMsg });
            console.error('Error del servidor:', data);
        }
    } catch (error) {
        console.error('Error al enviar la solicitud:', error);
        Swal.fire({ icon: 'error', title: 'Error', text: error.message || 'Error al enviar la solicitud. Intente nuevamente.' });
    } finally {
        if (btnEnviar) {
            btnEnviar.disabled = false;
            btnEnviar.classList.remove('opacity-60', 'cursor-not-allowed');
        }
    }
});

// Inicializar cuando el DOM esté listo
function initializeWizard() {
    // Asegurar que el botón Enviar esté oculto al inicio - FORZAR OCULTO
    const btnEnviar = document.getElementById('btnEnviar');
    const btnAnterior = document.getElementById('btnAnterior');
    const btnSiguiente = document.getElementById('btnSiguiente');
    
    if (btnEnviar) {
        btnEnviar.classList.add('hidden');
        btnEnviar.style.setProperty('display', 'none', 'important');
    }
    if (btnAnterior) {
        btnAnterior.classList.add('hidden');
    }
    if (btnSiguiente) {
        btnSiguiente.classList.remove('hidden');
        btnSiguiente.style.removeProperty('display'); // Asegurar que esté visible
    }
    
    showStep(1);
    initRecintoLock();
    initCalendarioDeportivo();
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeWizard);
} else {
    // DOM ya está listo, ejecutar inmediatamente
    setTimeout(initializeWizard, 100);
}
</script>
@endpush
@endsection
