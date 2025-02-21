#!/bin/bash

# Generar archivo .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Limpiar y regenerar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Iniciar Apache
apache2-foreground