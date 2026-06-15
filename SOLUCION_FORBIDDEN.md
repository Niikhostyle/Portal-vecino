# 🔧 Solución Error "Forbidden" en Producción

## ⚠️ Error: "Forbidden - You don't have permission to access this resource"

Este error generalmente se debe a problemas de permisos o configuración faltante. Sigue estos pasos en orden:

---

## ✅ Paso 1: Verificar Archivos Faltantes

### 1.1 Archivo `.env` en Producción

**CRÍTICO:** Asegúrate de tener un archivo `.env` en la raíz del proyecto en producción.

```bash
# En el servidor, verifica que existe
ls -la .env
```

Si no existe, créalo copiando desde `.env.example`:

```bash
cp .env.example .env
```

### 1.2 Generar APP_KEY

```bash
php artisan key:generate
```

### 1.3 Configurar Variables en `.env`

Edita el `.env` en producción con estos valores:

```env
APP_NAME="Portal Ciudadano"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_GENERADA_AQUI
APP_DEBUG=false
APP_URL=https://portalvecino.chanco.cl

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario_db
DB_PASSWORD=password_db

# Clave Única Configuration
CLAVEUNICA_CLIENT_ID=tu_client_id_de_produccion
CLAVEUNICA_CLIENT_SECRET=tu_client_secret_de_produccion
CLAVEUNICA_REDIRECT_URI=https://portalvecino.chanco.cl/callback
```

---

## ✅ Paso 2: Instalar Dependencias

### 2.1 Instalar Dependencias PHP (Composer)

```bash
composer install --no-dev --optimize-autoloader
```

**⚠️ IMPORTANTE:** Si no subiste la carpeta `vendor/`, debes ejecutar esto en el servidor.

### 2.2 Instalar Dependencias Node (si aplica)

```bash
npm install
npm run build
```

---

## ✅ Paso 3: Configurar Permisos (CRÍTICO)

Los permisos incorrectos son la causa más común del error "Forbidden".

### 3.1 Permisos de Carpetas

```bash
# Dar permisos de escritura a storage y bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# O si usas otro usuario web:
chown -R tu_usuario_web:tu_grupo_web storage bootstrap/cache
```

### 3.2 Permisos de Archivos

```bash
# Permisos generales del proyecto
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Permisos especiales para storage y cache
chmod -R 775 storage bootstrap/cache
```

### 3.3 Verificar Propietario

```bash
# Ver quién es el propietario actual
ls -la storage/
ls -la bootstrap/cache/

# Cambiar propietario si es necesario (ejemplo con www-data)
chown -R www-data:www-data storage bootstrap/cache
```

**Nota:** El usuario web puede ser `www-data`, `apache`, `nginx`, `httpd` o el usuario de tu hosting. Consulta con tu proveedor.

---

## ✅ Paso 4: Verificar Configuración del Servidor Web

### 4.1 Apache (.htaccess)

Verifica que el archivo `public/.htaccess` existe y tiene permisos de lectura:

```bash
ls -la public/.htaccess
chmod 644 public/.htaccess
```

Verifica que Apache tenga `mod_rewrite` habilitado:

```bash
# En Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 4.2 Nginx

Si usas Nginx, verifica que la configuración apunte a `public/`:

```nginx
server {
    listen 80;
    server_name portalvecino.chanco.cl;
    root /ruta/completa/al/proyecto/public;
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

**⚠️ IMPORTANTE:** El `root` debe apuntar a la carpeta `public/`, no a la raíz del proyecto.

---

## ✅ Paso 5: Limpiar y Optimizar Cachés

```bash
# Limpiar todos los cachés
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## ✅ Paso 6: Verificar Estructura de Carpetas

Asegúrate de que estas carpetas existan y tengan permisos correctos:

```bash
# Crear carpetas si no existen
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Dar permisos
chmod -R 775 storage bootstrap/cache
```

---

## ✅ Paso 7: Verificar Logs de Error

### 7.1 Logs de Laravel

```bash
tail -f storage/logs/laravel.log
```

### 7.2 Logs del Servidor Web

**Apache:**
```bash
tail -f /var/log/apache2/error.log
# O en algunos sistemas:
tail -f /var/log/httpd/error_log
```

**Nginx:**
```bash
tail -f /var/log/nginx/error.log
```

---

## ✅ Paso 8: Verificar que `public/` es el DocumentRoot

**CRÍTICO:** El servidor web debe apuntar a la carpeta `public/`, NO a la raíz del proyecto.

### Apache Virtual Host

```apache
<VirtualHost *:80>
    ServerName portalvecino.chanco.cl
    DocumentRoot /ruta/completa/al/proyecto/public
    
    <Directory /ruta/completa/al/proyecto/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

```nginx
server {
    listen 80;
    server_name portalvecino.chanco.cl;
    root /ruta/completa/al/proyecto/public;  # ← Debe apuntar a public/
    index index.php;
    # ... resto de configuración
}
```

---

## 🔍 Checklist Rápido

Ejecuta estos comandos en orden en el servidor:

```bash
# 1. Verificar .env
[ -f .env ] && echo "✅ .env existe" || echo "❌ .env NO existe - CREARLO"

# 2. Verificar vendor/
[ -d vendor ] && echo "✅ vendor/ existe" || echo "❌ vendor/ NO existe - Ejecutar: composer install"

# 3. Verificar permisos storage
[ -w storage ] && echo "✅ storage tiene permisos de escritura" || echo "❌ storage sin permisos - chmod 775 storage"

# 4. Verificar permisos bootstrap/cache
[ -w bootstrap/cache ] && echo "✅ bootstrap/cache tiene permisos" || echo "❌ bootstrap/cache sin permisos - chmod 775 bootstrap/cache"

# 5. Verificar public/.htaccess (Apache)
[ -f public/.htaccess ] && echo "✅ .htaccess existe" || echo "❌ .htaccess NO existe"

# 6. Verificar APP_KEY
php artisan tinker --execute="echo config('app.key') ? '✅ APP_KEY configurado' : '❌ APP_KEY NO configurado'"
```

---

## 🚨 Soluciones Específicas por Hosting

### cPanel / Hosting Compartido

1. **DocumentRoot:** Debe apuntar a `public_html/public` o crear un `.htaccess` en `public_html` que redirija:
   ```apache
   RewriteEngine On
   RewriteRule ^(.*)$ public/$1 [L]
   ```

2. **Permisos:** Usa el File Manager del cPanel para dar permisos 755 a carpetas y 644 a archivos.

### VPS / Servidor Dedicado

Sigue los pasos anteriores. Asegúrate de que:
- PHP está instalado y configurado
- Composer está instalado
- El servidor web (Apache/Nginx) está configurado correctamente

---

## 📞 Si el Problema Persiste

1. Revisa los logs: `storage/logs/laravel.log`
2. Verifica los logs del servidor web
3. Revisa que todas las variables `.env` estén correctas
4. Verifica que el DocumentRoot apunte a `public/`
5. Contacta a tu proveedor de hosting si es necesario

---

## ✅ Comandos de Verificación Final

```bash
# Verificar que todo está bien
php artisan about

# Probar la aplicación
php artisan serve  # Solo para pruebas locales, no usar en producción
```
