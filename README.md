# Portal Ciudadano Municipal

Sistema de gestión de solicitudes ciudadanas tipo OIRS (Oficina de Informaciones, Reclamaciones y Sugerencias) con flujo por etapas y dashboards por rol.

## Requisitos

- PHP 8.2 o superior
- MySQL/MariaDB 5.7 o superior
- Composer
- Apache/Nginx con mod_rewrite habilitado

## Instalación

1. **Clonar o descargar el proyecto**

2. **Instalar dependencias de Composer**
```bash
composer install
```

3. **Configurar el archivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` y configurar:
```env
APP_NAME="Portal Ciudadano"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_ciudadano
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

FILESYSTEM_DISK=private
```

4. **Crear la base de datos**
```sql
CREATE DATABASE portal_ciudadano CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

6. **Configurar permisos de carpetas**
```bash
# En Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# En Windows, asegurarse de que la carpeta storage tenga permisos de escritura
```

7. **Configurar almacenamiento de archivos**

Los archivos adjuntos se guardan en `storage/app/private/adjuntos`. Asegúrate de que esta carpeta tenga permisos de escritura.

## Usuarios de Prueba

El seeder crea los siguientes usuarios:

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | nalvarez@chanco.cl | admin123 |
| Oficina de Partes | oficinapartes@chanco.cl | op123 |
| Funcionario | funcionario1@chanco.cl | funcionario123 |
| Vecino | vecino1@gmail.com | vecino123 |

## Estructura del Proyecto

```
portal_ciudadano/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── FuncionarioController.php
│   │   │   ├── OficinaPartesController.php
│   │   │   ├── RecintosController.php
│   │   │   └── VecinoController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/
│       ├── Recinto.php
│       ├── RecintoReserva.php
│       ├── Solicitud.php
│       ├── SolicitudAdjunto.php
│       ├── SolicitudEvento.php
│       ├── SolicitudTipo.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RecintoSeeder.php
│       ├── SolicitudTipoSeeder.php
│       └── UserSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── funcionario/
│       ├── layouts/
│       ├── op/
│       ├── recintos/
│       └── vecino/
└── routes/
    └── web.php
```

## Roles y Permisos

### Administrador
- Acceso completo al sistema
- Gestión de usuarios
- Gestión de catálogo de solicitudes
- Ver reportes y estadísticas
- Puede derivar, responder y rechazar solicitudes

### Oficina de Partes (OP)
- Ver todas las solicitudes nuevas
- Derivar solicitudes a funcionarios
- Rechazar solicitudes (con motivo obligatorio)
- Ver calendario de recintos

### Funcionario
- Ver solicitudes asignadas
- Cambiar estado de solicitudes
- Responder solicitudes
- Solicitar información adicional al vecino
- Ver calendario de recintos

### Vecino
- Crear solicitudes mediante wizard
- Ver historial de sus solicitudes
- Ver estado y línea de tiempo de solicitudes
- Descargar adjuntos
- Ver calendario de recintos

## Tipos de Solicitudes Disponibles

1. **Credencial de Discapacidad** (Sección: SOCIAL)
   - Requisitos: Fotocopia de Carnet, Documentación médica

2. **Patentes Comerciales, Profesionales e Industriales** (Sección: TRÁNSITO Y RENTAS)
   - Formulario con datos del solicitante y detalle del trámite

3. **Traslado de vehículos - Permiso de Circulación** (Sección: TRÁNSITO Y RENTAS)
   - Requisitos: No tener deuda, Permiso de circulación, Padrón, SOAP, Revisión técnica

4. **Movilización** (Sección: MOVILIZACIÓN)
   - Requisitos: Fotocopia de carnet, Informe médico (sin datos sensibles)

5. **Recintos Municipales** (Sección: RECINTOS MUNICIPALES)
   - Incluye calendario de disponibilidad

6. **Recintos Deportivos** (Sección: DEPORTES)
   - Incluye calendario de disponibilidad

## Estados de Solicitudes

- **ENVIADA**: Creada por el vecino
- **EN REVISIÓN OP**: En revisión por Oficina de Partes
- **DERIVADA**: Asignada a un funcionario
- **EN GESTIÓN**: Funcionario trabajando en la solicitud
- **RESPONDIDA**: Con respuesta y adjuntos para el vecino
- **RECHAZADA**: Rechazada por OP con motivo

## Generación de Folios

Los folios se generan automáticamente con el formato:
```
CHANCO-YYYY-NNNNNN
```
Donde YYYY es el año y NNNNNN es un número secuencial de 6 dígitos.

## Seguridad

- Autenticación mediante sesiones
- Protección CSRF en todos los formularios
- Validación server-side de todos los datos
- Contraseñas hasheadas con bcrypt
- Control de acceso por roles mediante middleware
- Archivos adjuntos almacenados fuera del webroot
- Validación de tipos y tamaños de archivo

## Configuración de Apache

Si usas Apache, asegúrate de tener habilitado mod_rewrite y configura un VirtualHost:

```apache
<VirtualHost *:80>
    ServerName portal.local
    DocumentRoot /ruta/al/proyecto/public
    
    <Directory /ruta/al/proyecto/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Configuración de Nginx

Para Nginx, configura el servidor:

```nginx
server {
    listen 80;
    server_name portal.local;
    root /ruta/al/proyecto/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Mantenimiento

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Optimizar para producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Soporte

Para problemas o consultas, contactar al administrador del sistema.

## Licencia

Este proyecto es software propietario desarrollado para uso municipal.
