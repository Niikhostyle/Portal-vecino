@extends('layouts.app')

@section('title', 'Registrar Ciudadano')
@section('header_title', 'Registrar Ciudadano')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-slate-900">Registrar Ciudadano</h1>
            <p class="mt-1 text-sm text-slate-600">Para ciudadanos que no han ingresado con Clave Única. Una vez registrado, podrá crear solicitudes en su nombre.</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('staff.vecinos.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nombre Completo <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Ej: Juan Pérez González">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rut" class="mb-2 block text-sm font-medium text-slate-700">RUT <span class="text-red-500">*</span></label>
                    <input type="text" id="rut" name="rut" value="{{ old('rut') }}" required
                           class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 @error('rut') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                           placeholder="12.345.678-9">
                    @error('rut')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                           placeholder="correo@ejemplo.com (opcional)">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('staff.crear-solicitud') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                        Cancelar
                    </a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Registrar Ciudadano
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
