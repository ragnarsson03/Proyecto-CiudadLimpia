@echo off
cd /d "c:\xampp\htdocs\Proyecto-CiudadLimpia"
php artisan queue:work --tries=3 >> "c:\xampp\htdocs\Proyecto-CiudadLimpia\storage\logs\queue-worker.log" 2>&1
