# 🔧 Solución Error "Forbidden" en Hosting Compartido

## ⚠️ Problema: "Forbidden - You don't have permission to access this resource"

En hosting compartido, el DocumentRoot generalmente apunta a la carpeta raíz del proyecto, pero Laravel necesita que apunte a `public/`.

---

## ✅ Solución Rápida (3 Pasos)

### Paso 1: Subir archivo `.htaccess` en la raíz

**IMPORTANTE:** Sube el archivo `.htaccess` que está en la raíz del proyecto (junto a `composer.json`). Este archivo redirige todo el tráfico a `public/`.

Si no lo tienes, créalo con este contenido:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirigir todo el tráfico a la carpeta public/
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Paso 2: Crear archivo `.env` en producción

En el servidor, crea un archivo `.env` en la raíz del proyecto con este contenido mínimo:

```env
APP_NAME="Portal Ciudadano"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://portalvecino.chanco.cl

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario_db
DB_PASSWORD=tu_password_db

SESSION_DRIVER=database
SESSION_LIFETIME=120

# Clave Única Configuration
CLAVEUNICA_CLIENT_ID=tu_client_id_aqui
CLAVEUNICA_CLIENT_SECRET=tu_client_secret_aqui
CLAVEUNICA_REDIRECT_URI=https://portalvecino.chanco.cl/callback
```

**Luego genera el APP_KEY:**
```bash
php artisan key:generate
```

### Paso 3: Configurar Permisos

Usa el File Manager de tu hosting (cPanel/Plesk) o FTP para dar estos permisos:

- **Carpetas:** `755` o `775`
  - `storage/`
  - `bootstrap/cache/`
  
- **Archivos:** `644` o `664`
  - Todos los archivos `.php`
  - `.env`
  - `.htaccess`

**Específicamente:**
```
storage/                    → 775
storage/logs/               → 775
storage/framework/         → 775
storage/framework/cache/   → 775
storage/framework/sessions/ → 775
storage/framework/views/    → 775
bootstrap/cache/            → 775
```

---

## 🔍 Verificación en cPanel

### 1. Verificar DocumentRoot

En cPanel → **Dominios** → **Configuración del dominio**:
- El DocumentRoot debe apuntar a la carpeta donde está tu proyecto
- NO debe apuntar directamente a `public/`

### 2. Verificar PHP Version

En cPanel → **Seleccionar versión de PHP**:
- Usa PHP 8.1 o superior
- Asegúrate de que esté habilitado `mod_rewrite`

### 3. Verificar que `.htaccess` esté activo

En cPanel → **Archivos** → Verifica que el archivo `.htaccess` en la raíz tenga permisos `644`

---

## 📋 Checklist Completo

- [ ] Archivo `.htaccess` en la raíz del proyecto (redirige a `public/`)
- [ ] Archivo `.env` creado en producción
- [ ] `APP_KEY` generado con `php artisan key:generate`
- [ ] Variables de Clave Única configuradas en `.env`
- [ ] Permisos `775` en `storage/` y `bootstrap/cache/`
- [ ] Carpeta `vendor/` subida o instalada con `composer install`
- [ ] Archivo `public/.htaccess` existe
- [ ] PHP 8.1+ configurado en el hosting

---

## 🚨 Si Aún No Funciona

### Opción A: Cambiar DocumentRoot (si tienes acceso)

Si puedes cambiar el DocumentRoot en el panel de control:
- Cambia el DocumentRoot para que apunte directamente a `public/`
- En este caso, NO necesitas el `.htaccess` en la raíz

### Opción B: Verificar Logs

1. **Logs de Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Logs del servidor (cPanel):**
   - Ve a **Errores** en cPanel
   - Revisa los errores recientes

### Opción C: Verificar Estructura de Carpetas

Asegúrate de que la estructura sea así:

```
tu_proyecto/
├── .htaccess          ← NUEVO: Redirige a public/
├── .env               ← NUEVO: Configuración de producción
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── .htaccess     ← Debe existir
│   ├── index.php
│   └── ...
├── resources/
├── routes/
├── storage/           ← Permisos 775
└── vendor/            ← Debe existir
```

---

## 📞 Comandos Útiles (si tienes SSH)

```bash
# Verificar permisos
ls -la storage/
ls -la bootstrap/cache/

# Cambiar permisos
chmod -R 775 storage bootstrap/cache

# Generar APP_KEY
php artisan key:generate

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan optimize

# Verificar configuración
php artisan about
```

---

## ✅ Solución Más Común

**En el 90% de los casos, el problema es:**

1. **Falta el `.htaccess` en la raíz** que redirige a `public/`
2. **Falta el archivo `.env`** en producción
3. **Permisos incorrectos** en `storage/` y `bootstrap/cache/`

**Solución rápida:**
1. Sube el `.htaccess` a la raíz del proyecto
2. Crea el `.env` con las variables necesarias
3. Genera el `APP_KEY`
4. Configura permisos `775` en `storage/` y `bootstrap/cache/`

---

## 🔗 Archivos que DEBES Subir

Asegúrate de haber subido:

- ✅ `.htaccess` (en la raíz) ← **CRÍTICO**
- ✅ `.env` (en la raíz, con tus datos de producción)
- ✅ `public/.htaccess` (debe existir)
- ✅ Toda la carpeta `public/`
- ✅ Carpeta `vendor/` (o ejecutar `composer install` en el servidor)
