@extends('layouts.app')

@section('title', 'Configuración')
@section('header_title', 'Configuración')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto max-w-3xl px-4 pt-8 pb-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Configuración del sistema</h1>
            <p class="mt-1 text-base text-slate-600">Activa o desactiva módulos visibles para los usuarios.</p>
        </div>

        <form action="{{ route('admin.configuracion.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Módulos</h2>
                </div>

                <div class="divide-y divide-slate-100">
                    {{-- Toggle: Módulo Recintos --}}
                    <label class="flex cursor-pointer items-center justify-between gap-4 px-6 py-5 hover:bg-slate-50/60">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg" style="background-color: #eff6ff; color: #2563eb;">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Módulo Recintos</p>
                                <p class="mt-0.5 text-sm text-slate-500">Muestra u oculta el botón "Recintos" en el menú para todos los usuarios.</p>
                            </div>
                        </div>

                        <input type="hidden" name="recintos_enabled" value="0">
                        <span class="relative inline-flex shrink-0">
                            <input type="checkbox" name="recintos_enabled" value="1" class="peer sr-only"
                                   {{ $config['recintos_enabled'] ? 'checked' : '' }}>
                            <span class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-blue-600"></span>
                            <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>

                <div class="flex justify-end border-t border-slate-200 px-6 py-4">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
