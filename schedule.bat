@echo off
cd /d "c:\xampp\htdocs\Proyecto-CiudadLimpia"
php artisan schedule:run >> "c:\xampp\htdocs\Proyecto-CiudadLimpia\storage\logs\scheduler.log" 2>&1
