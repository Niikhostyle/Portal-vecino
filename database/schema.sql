-- Script SQL para crear la base de datos del Portal Ciudadano
-- Ejecutar este script antes de correr las migraciones si prefieres crear la BD manualmente

CREATE DATABASE IF NOT EXISTS portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE portal;

-- Nota: Las tablas se crearán automáticamente mediante las migraciones de Laravel
-- Este script solo crea la base de datos

-- Para ejecutar las migraciones después de crear la BD:
-- php artisan migrate --seed
