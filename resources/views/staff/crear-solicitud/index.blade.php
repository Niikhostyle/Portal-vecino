@extends('layouts.app')

@section('title', 'Crear Solicitud en Nombre de Ciudadano')
@section('header_title', 'Crear Solicitud')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-slate-900">Crear Solicitud en Nombre de Ciudadano</h1>
            <p class="mt-1 text-sm text-slate-600">Selecciona el ciudadano y el tipo de solicitud. No todos los ciudadanos saben usar el sistema.</p>
        </div>

        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                <label class="block text-sm font-medium text-slate-700">Ciudadano <span class="text-red-500">*</span></label>
                <a href="{{ route('staff.registrar-ciudadano') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-blue-600 bg-white px-3 py-1.5 text-sm font-medium text-blue-600 transition-colors hover:bg-blue-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Registrar ciudadano
                </a>
            </div>
            <select id="vecinoSelect" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0">
                <option value="">Seleccione un ciudadano...</option>
                @foreach($vecinos as $v)
                    <option value="{{ $v->id }}" {{ ($vecinoPreseleccionado ?? '') == $v->id ? 'selected' : '' }}>{{ $v->name }} @if($v->email)({{ $v->email }})@endif</option>
                @endforeach
            </select>
            @if($vecinos->isEmpty())
                <p class="mt-2 text-sm text-amber-700">No hay ciudadanos registrados. <a href="{{ route('staff.registrar-ciudadano') }}" class="font-medium underline">Registrar el primer ciudadano</a></p>
            @endif
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-medium text-slate-900">Tipo de solicitud</h2>
            <p class="text-sm text-slate-600">Selecciona el trámite a realizar para el ciudadano</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($tipos_solicitud as $seccion => $tipos)
                <div class="flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all hover:shadow-md hover:border-slate-300">
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                        <h2 class="text-sm font-semibold text-slate-900">{{ $seccion }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $tipos->count() }} {{ $tipos->count() === 1 ? 'trámite' : 'trámites' }}</p>
                    </div>
                    <div class="flex-1 p-3">
                        <div class="space-y-2">
                            @foreach($tipos as $tipo)
                                <a href="#" data-tipo-id="{{ $tipo->id }}" data-tipo-titulo="{{ $tipo->titulo }}" class="staff-tipo-link group flex items-start gap-2 rounded-lg border border-slate-200 bg-white p-3 transition-all hover:border-blue-200 hover:bg-blue-50/50">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 transition-colors group-hover:bg-blue-100">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-slate-900 transition-colors group-hover:text-slate-700">{{ $tipo->titulo }}</h3>
                                        @if($tipo->descripcion)
                                            <p class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-slate-600">{{ Str::limit($tipo->descripcion, 60) }}</p>
                                        @endif
                                    </div>
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400 transition-transform group-hover:translate-x-0.5 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.staff-tipo-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const vecinoId = document.getElementById('vecinoSelect').value;
        const tipoId = this.dataset.tipoId;
        if (!vecinoId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Seleccione ciudadano', text: 'Debe seleccionar un ciudadano antes de continuar.' });
            } else {
                alert('Debe seleccionar un ciudadano antes de continuar.');
            }
            return;
        }
        window.location.href = '{{ url("staff/crear-solicitud") }}/' + tipoId + '?vecino_id=' + vecinoId;
    });
});
</script>
@endsection
