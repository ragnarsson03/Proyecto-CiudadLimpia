#!/bin/bash

# Generar archivo .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones (ignorando errores de tablas existentes)
php artisan migrate --force --seed || true

# Configurar permisos
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Iniciar Apache
apache2-foreground