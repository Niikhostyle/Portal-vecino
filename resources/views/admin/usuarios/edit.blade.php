@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Editar Usuario</h1>
            <p class="mt-1 text-base text-slate-600">Actualiza la información del usuario</p>
        </div>
        
        <!-- Form -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nombre Completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $usuario->name) }}" required class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Contraseña</label>
                    <input type="password" id="password" name="password" minlength="6" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Dejar en blanco para no cambiar">
                    <p class="mt-1.5 text-xs text-slate-500">Dejar en blanco para mantener la contraseña actual</p>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rol" class="mb-2 block text-sm font-medium text-slate-700">Rol</label>
                    <select id="rol" name="rol" required class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('rol') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="administrador" {{ old('rol', $usuario->rol) === 'administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="oficina_partes" {{ old('rol', $usuario->rol) === 'oficina_partes' ? 'selected' : '' }}>Oficina de Partes</option>
                        <option value="funcionario" {{ old('rol', $usuario->rol) === 'funcionario' ? 'selected' : '' }}>Funcionario</option>
                        <option value="vecino" {{ old('rol', $usuario->rol) === 'vecino' ? 'selected' : '' }}>Vecino</option>
                    </select>
                    @error('rol')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="mb-2 block text-sm font-medium text-slate-700">Estado</label>
                    <select id="estado" name="estado" required class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors @error('estado') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="activo" {{ old('estado', $usuario->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $usuario->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.usuarios') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                        Cancelar
                    </a>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
