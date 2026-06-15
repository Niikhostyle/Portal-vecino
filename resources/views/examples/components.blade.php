@extends('layouts.app')

@section('title', 'Ejemplos de Componentes')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
@endpush

@section('content')
<div class="flex-1 overflow-y-auto px-4 py-6 lg:px-8 lg:py-10 max-w-4xl mx-auto">
    <header class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Ejemplos de Componentes</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Componentes Blade inspirados en shadcn/ui</p>
    </header>

    <div class="space-y-8">
        <!-- Buttons -->
        <section>
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-50">Buttons</h2>
            <div class="flex flex-wrap gap-4">
                <x-button variant="default">Default</x-button>
                <x-button variant="destructive">Destructive</x-button>
                <x-button variant="outline">Outline</x-button>
                <x-button variant="secondary">Secondary</x-button>
                <x-button variant="ghost">Ghost</x-button>
                <x-button variant="link">Link</x-button>
            </div>
            <div class="mt-4 flex flex-wrap gap-4">
                <x-button size="sm">Small</x-button>
                <x-button size="default">Default</x-button>
                <x-button size="lg">Large</x-button>
            </div>
        </section>

        <!-- Badges -->
        <section>
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-50">Badges</h2>
            <div class="flex flex-wrap gap-4">
                <x-badge variant="default">Default</x-badge>
                <x-badge variant="secondary">Secondary</x-badge>
                <x-badge variant="destructive">Destructive</x-badge>
                <x-badge variant="outline">Outline</x-badge>
                <x-badge variant="success">Success</x-badge>
                <x-badge variant="warning">Warning</x-badge>
                <x-badge variant="info">Info</x-badge>
                <x-badge variant="enviada">Enviada</x-badge>
                <x-badge variant="respondida">Respondida</x-badge>
            </div>
        </section>

        <!-- Switch -->
        <section>
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-50">Switch</h2>
            <div class="flex items-center gap-4">
                <x-switch checked="{{ true }}" />
                <x-switch checked="{{ false }}" />
                <x-switch disabled="{{ true }}" />
            </div>
        </section>

        <!-- Alert -->
        <section>
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-50">Alerts</h2>
            <div class="space-y-4">
                <x-alert variant="default">
                    <x-alert-title>Información</x-alert-title>
                    <x-alert-description>Este es un mensaje informativo.</x-alert-description>
                </x-alert>
                <x-alert variant="success">
                    <x-alert-title>Éxito</x-alert-title>
                    <x-alert-description>Operación completada correctamente.</x-alert-description>
                </x-alert>
                <x-alert variant="warning">
                    <x-alert-title>Advertencia</x-alert-title>
                    <x-alert-description>Por favor, revise los datos ingresados.</x-alert-description>
                </x-alert>
                <x-alert variant="destructive">
                    <x-alert-title>Error</x-alert-title>
                    <x-alert-description>Ha ocurrido un error al procesar la solicitud.</x-alert-description>
                </x-alert>
            </div>
        </section>

        <!-- Dialog -->
        <section>
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-50">Dialog</h2>
            <x-button variant="default" onclick="document.getElementById('example-dialog').__x.$data.open = true">
                Abrir Dialog
            </x-button>
            <x-dialog id="example-dialog" open="{{ false }}">
                <x-dialog-header>
                    <x-dialog-title>Confirmar Acción</x-dialog-title>
                    <x-dialog-description>
                        ¿Está seguro de realizar esta acción? Esta operación no se puede deshacer.
                    </x-dialog-description>
                </x-dialog-header>
                <x-dialog-content>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Esta es una acción importante que requiere confirmación.
                    </p>
                </x-dialog-content>
                <x-dialog-footer>
                    <x-button variant="outline" onclick="document.getElementById('example-dialog').__x.$data.open = false">
                        Cancelar
                    </x-button>
                    <x-button variant="default" onclick="document.getElementById('example-dialog').__x.$data.open = false">
                        Confirmar
                    </x-button>
                </x-dialog-footer>
            </x-dialog>
        </section>

        <!-- Dropdown Menu -->
        <section>
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-50">Dropdown Menu</h2>
            <x-dropdown-menu>
                <x-dropdown-trigger>
                    <x-button variant="outline">
                        Menú
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </x-button>
                </x-dropdown-trigger>
                <x-dropdown-content>
                    <x-dropdown-item href="#">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Perfil
                    </x-dropdown-item>
                    <x-dropdown-item href="#">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configuración
                    </x-dropdown-item>
                    <x-separator />
                    <x-dropdown-item href="#">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Cerrar Sesión
                    </x-dropdown-item>
                </x-dropdown-content>
            </x-dropdown-menu>
        </section>

        <!-- Form Elements -->
        <section>
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-50">Form Elements</h2>
            <x-card class="p-6">
                <form class="space-y-4">
                    <div>
                        <x-label for="example-input">Input</x-label>
                        <x-input id="example-input" type="text" placeholder="Escribe algo..." class="mt-1" />
                    </div>
                    <div>
                        <x-label for="example-select">Select</x-label>
                        <x-select id="example-select" class="mt-1">
                            <option value="">Seleccione...</option>
                            <option value="1">Opción 1</option>
                            <option value="2">Opción 2</option>
                        </x-select>
                    </div>
                    <div>
                        <x-label for="example-textarea">Textarea</x-label>
                        <x-textarea id="example-textarea" rows="4" placeholder="Escribe un mensaje..." class="mt-1" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-checkbox id="example-checkbox" />
                        <x-label for="example-checkbox">Acepto los términos y condiciones</x-label>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-switch id="example-switch" />
                        <x-label for="example-switch">Notificaciones activadas</x-label>
                    </div>
                </form>
            </x-card>
        </section>
    </div>
</div>
@endsection
