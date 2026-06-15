# Despliegue — GitHub y producción

## 1. Subir el proyecto a GitHub (desde tu PC)

```bash
cd portal_ciudadano
git init
git add .
git commit -m "Initial commit: Portal Ciudadano Municipal"
```

En [github.com](https://github.com/new) crea un repositorio **vacío** (sin README ni .gitignore). Luego:

```bash
git branch -M main
git remote add origin https://github.com/TU_USUARIO/portal-ciudadano.git
git push -u origin main
```

> **Importante:** el archivo `.env` **no se sube** (está en `.gitignore`). Las credenciales van solo en el servidor.

---

## 2. Clonar en producción

Con SSH en el servidor (cPanel Terminal o acceso SSH):

```bash
cd ~/public_html   # o la carpeta donde alojarás el proyecto
git clone https://github.com/TU_USUARIO/portal-ciudadano.git portal_ciudadano
cd portal_ciudadano
```

Si no tienes SSH, clona en tu PC y sube por FTP/cPanel File Manager (menos recomendable para actualizaciones).

---

## 3. Configurar en producción

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con los valores de producción:

```env
APP_NAME="Portal Ciudadano"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://portalvecino.chanco.cl

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

FILESYSTEM_DISK=private

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@chanco.cl
MAIL_PASSWORD=contraseña_de_aplicacion
MAIL_FROM_ADDRESS="noreply@chanco.cl"

CLAVEUNICA_CLIENT_ID=tu_client_id
CLAVEUNICA_CLIENT_SECRET=tu_client_secret
CLAVEUNICA_REDIRECT_URI=https://portalvecino.chanco.cl/callback
```

Instalar dependencias y base de datos:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Permisos (Linux):

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 4. Hosting compartido (cPanel)

- Debe existir `.htaccess` en la **raíz** del proyecto (redirige a `public/`).
- El DocumentRoot puede apuntar a la raíz del proyecto o directamente a `public/`.
- Ver también: `SOLUCION_HOSTING_COMPARTIDO.md` y `SOLUCION_FORBIDDEN.md`.

---

## 5. Clave Única

En el portal de ClaveÚnica, registra la URL de callback de **producción**:

```
https://portalvecino.chanco.cl/callback
```

Debe coincidir exactamente con `CLAVEUNICA_REDIRECT_URI` (sin barra final en `APP_URL`).

---

## 6. Actualizar producción (después de cambios)

```bash
cd portal_ciudadano
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Si cambias CSS/JS en local:

```bash
npm ci && npm run build
git add public/build && git commit -m "Rebuild assets" && git push
```

---

## Checklist rápido

- [ ] Repo creado en GitHub y código subido
- [ ] `.env` configurado en producción (nunca en el repo)
- [ ] `APP_KEY` generado
- [ ] Base de datos creada y migraciones ejecutadas
- [ ] Permisos `775` en `storage/` y `bootstrap/cache/`
- [ ] Callback de ClaveÚnica apunta al dominio de producción
- [ ] SMTP configurado para notificaciones por correo
