<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InfraestructuraController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController; // Agregado
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta principal
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rutas de autenticación y dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Infraestructura
    Route::resource('infraestructura', InfraestructuraController::class);

    // Rutas de Incidencia
    Route::resource('incidencia', IncidenciaController::class);

    // Rutas de Exportación
    Route::get('/export/incidencias/{format}', [ExportController::class, 'incidencias'])
        ->name('export.incidencias')
        ->where('format', 'pdf|excel');
});

require __DIR__.'/auth.php';
