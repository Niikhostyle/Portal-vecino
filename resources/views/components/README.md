# Componentes Blade - Estilo shadcn/ui

Esta carpeta contiene componentes Blade reutilizables inspirados en el diseño de shadcn/ui.

## Componentes Disponibles

### Card
```blade
<x-card>
    <x-card-header>
        <x-card-title>Título</x-card-title>
        <x-card-description>Descripción</x-card-description>
    </x-card-header>
    <x-card-content>
        Contenido aquí
    </x-card-content>
</x-card>
```

### Button
```blade
<x-button variant="default" size="sm">Click me</x-button>
<x-button variant="outline" href="/route">Link Button</x-button>
```

Variantes: `default`, `destructive`, `outline`, `secondary`, `ghost`, `link`
Tamaños: `default`, `sm`, `lg`, `icon`

### Badge
```blade
<x-badge variant="success">Aprobado</x-badge>
<x-badge variant="enviada">Enviada</x-badge>
```

Variantes: `default`, `secondary`, `destructive`, `outline`, `success`, `warning`, `info`, `enviada`, `en_revision`, `respondida`, `rechazada`

### Input
```blade
<x-input type="text" name="email" error="{{ $errors->has('email') }}" />
```

### Select
```blade
<x-select name="rol" error="{{ $errors->has('rol') }}">
    <option value="">Seleccione...</option>
    <option value="admin">Admin</option>
</x-select>
```

### Textarea
```blade
<x-textarea name="description" rows="4" error="{{ $errors->has('description') }}" />
```

### Checkbox
```blade
<x-checkbox name="remember" checked="{{ old('remember') }}" />
```

### Switch
```blade
<x-switch name="active" checked="{{ $user->active }}" />
```

### Label
```blade
<x-label for="email">Correo Electrónico</x-label>
```

### Alert
```blade
<x-alert variant="success">
    <x-alert-title>Éxito</x-alert-title>
    <x-alert-description>Operación completada correctamente.</x-alert-description>
</x-alert>
```

Variantes: `default`, `destructive`, `success`, `warning`, `info`

### Dialog
```blade
<x-dialog open="{{ false }}">
    <x-dialog-header>
        <x-dialog-title>Confirmar</x-dialog-title>
        <x-dialog-description>¿Está seguro?</x-dialog-description>
    </x-dialog-header>
    <x-dialog-content>
        Contenido del diálogo
    </x-dialog-content>
    <x-dialog-footer>
        <x-button variant="outline">Cancelar</x-button>
        <x-button variant="default">Confirmar</x-button>
    </x-dialog-footer>
</x-dialog>
```

### Dropdown Menu
```blade
<x-dropdown-menu>
    <x-dropdown-trigger>
        <x-button variant="outline">Menú</x-button>
    </x-dropdown-trigger>
    <x-dropdown-content>
        <x-dropdown-item href="/profile">Perfil</x-dropdown-item>
        <x-dropdown-item href="/settings">Configuración</x-dropdown-item>
    </x-dropdown-content>
</x-dropdown-menu>
```

### Separator
```blade
<x-separator />
<x-separator orientation="vertical" />
```

## Uso

Todos los componentes soportan:
- Atributos HTML estándar (`class`, `id`, `data-*`, etc.)
- Modo oscuro automático con clases `dark:`
- Variantes de estilo
- Accesibilidad básica

## Personalización

Los componentes usan clases de Tailwind CSS con la paleta Slate. Puedes personalizar los colores modificando las clases en cada componente.
