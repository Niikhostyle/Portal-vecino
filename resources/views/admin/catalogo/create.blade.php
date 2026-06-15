@extends('layouts.app')

@section('title', 'Crear Nuevo Trámite')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Crear Nuevo Trámite</h1>
            <p class="mt-1 text-base text-slate-600">Añade un nuevo tipo de solicitud al catálogo</p>
        </div>
        
        <!-- Form -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.catalogo.store') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="codigo" class="mb-2 block text-sm font-medium text-slate-700">Código <span class="text-red-500">*</span></label>
                    <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" required maxlength="50" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('codigo') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Ej: DEPORTES-001">
                    <p class="mt-1.5 text-xs text-slate-500">Código único para identificar el trámite (máx. 50 caracteres)</p>
                    @error('codigo')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="titulo" class="mb-2 block text-sm font-medium text-slate-700">Título <span class="text-red-500">*</span></label>
                    <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="255" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('titulo') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Ej: Reserva de Cancha de Fútbol">
                    @error('titulo')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="seccion" class="mb-2 block text-sm font-medium text-slate-700">Sección/Categoría <span class="text-red-500">*</span></label>
                    <input type="text" id="seccion" name="seccion" value="{{ old('seccion') }}" required maxlength="255" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('seccion') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Ej: Deportes, Servicios Municipales, etc.">
                    <p class="mt-1.5 text-xs text-slate-500">La sección agrupa los trámites en categorías. Si la sección no existe, se creará automáticamente.</p>
                    @error('seccion')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="descripcion" class="mb-2 block text-sm font-medium text-slate-700">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors resize-none @error('descripcion') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Descripción breve del trámite...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-3">Requisitos del trámite</h3>
                    <p class="text-xs text-slate-600 mb-4">Selecciona qué datos deberá completar el ciudadano para este trámite.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach(config('tramites.campos', []) as $key => $config)
                            <label class="flex items-center rounded-lg border border-slate-200 p-3 hover:bg-slate-50">
                                <input type="checkbox" name="campos[]" value="{{ $key }}" {{ in_array($key, old('campos', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                                <span class="ml-2 text-sm text-slate-700">{{ $config['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="bloqueRecintosCreate" class="mt-4 p-4 rounded-lg border border-slate-200 bg-slate-50 {{ in_array('recinto', old('campos', [])) ? '' : 'hidden' }}">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Recintos disponibles para este trámite</label>
                        <p class="mb-3 text-xs text-slate-600">Selecciona los recintos que el ciudadano podrá elegir. Si no seleccionas ninguno, se mostrarán todos los recintos activos.</p>
                        <select name="recintos_ids[]" multiple size="6" class="h-auto w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0">
                            @foreach($recintos ?? [] as $recinto)
                                <option value="{{ $recinto->id }}" {{ in_array($recinto->id, old('recintos_ids', [])) ? 'selected' : '' }}>
                                    {{ $recinto->nombre }} @if($recinto->tipo)({{ ucfirst(str_replace('_', ' ', $recinto->tipo)) }})@endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-slate-500">Mantén Ctrl (o Cmd) para seleccionar varios</p>
                    </div>
                    <div id="bloqueUsarHorariosCreate" class="mt-4 p-4 rounded-lg border border-blue-200 bg-blue-50 hidden">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="usar_horarios_disponibles" value="1" {{ old('usar_horarios_disponibles') ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Usar fecha + horarios disponibles (como recintos deportivos)</span>
                                <p class="mt-0.5 text-xs text-slate-600">El ciudadano selecciona solo la fecha; las horas disponibles se cargan según el día y el recinto elegido.</p>
                            </div>
                        </label>
                    </div>
                    <script>
                    document.querySelectorAll('input[name="campos[]"]').forEach(cb => {
                        cb.addEventListener('change', function() {
                            const recinto = document.querySelector('input[name="campos[]"][value="recinto"]')?.checked;
                            const fi = document.querySelector('input[name="campos[]"][value="fecha_inicio"]')?.checked;
                            const ff = document.querySelector('input[name="campos[]"][value="fecha_fin"]')?.checked;
                            const hi = document.querySelector('input[name="campos[]"][value="hora_inicio"]')?.checked;
                            const hf = document.querySelector('input[name="campos[]"][value="hora_fin"]')?.checked;
                            const bloqueRecintos = document.getElementById('bloqueRecintosCreate');
                            const bloqueHorarios = document.getElementById('bloqueUsarHorariosCreate');
                            if (bloqueRecintos) bloqueRecintos.classList.toggle('hidden', !recinto);
                            if (bloqueHorarios) bloqueHorarios.classList.toggle('hidden', !(recinto && fi && ff && hi && hf));
                        });
                    });
                    </script>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="requiere_adjuntos" value="1" id="requiere_adjuntos" {{ old('requiere_adjuntos', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                    <label for="requiere_adjuntos" class="ml-2 block text-sm text-slate-700">
                        Permitir adjuntar documentos
                    </label>
                </div>

                <div class="border-t border-slate-200 pt-6" x-data="{ docs: {{ json_encode(old('documentos_requeridos', [''])) }} }">
                    <h3 class="text-sm font-semibold text-slate-900 mb-2">Documentos requeridos (lista para el ciudadano)</h3>
                    <p class="text-xs text-slate-600 mb-3">Nombres de documentos que el ciudadano debe entregar. Añade o deja en blanco los que no apliquen.</p>
                    <template x-for="(doc, i) in docs" :key="i">
                        <div class="flex gap-2 mb-2">
                            <input type="text" :name="'documentos_requeridos[' + i + ']'" x-model="docs[i]" placeholder="Ej: Cédula de identidad" class="flex-1 h-10 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <button type="button" @click="docs.splice(i, 1)" x-show="docs.length > 1" class="rounded-lg border border-red-200 bg-white px-3 text-red-600 hover:bg-red-50 text-sm">Quitar</button>
                        </div>
                    </template>
                    <button type="button" @click="docs.push('')" class="mt-2 text-sm font-medium text-slate-600 hover:text-slate-900">+ Añadir documento requerido</button>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="activo" value="1" id="activo" {{ old('activo', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                    <label for="activo" class="ml-2 block text-sm text-slate-700">
                        Trámite habilitado (visible para vecinos y personal al crear solicitudes)
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.catalogo') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                        Cancelar
                    </a>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                        Crear Trámite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
