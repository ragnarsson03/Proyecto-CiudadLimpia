<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InfraestructuraController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\MantenimientoPreventivoController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\RutaController;
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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Infraestructura
    Route::resource('infraestructura', InfraestructuraController::class);

    // Rutas de Incidencia
    Route::resource('incidencia', IncidenciaController::class);

    // Rutas de Personal
    Route::resource('personal', PersonalController::class);

    // Rutas de Materiales
    Route::resource('materiales', MaterialController::class);
    Route::post('/materiales/{material}/ajustar-inventario', [MaterialController::class, 'ajustarInventario'])
        ->name('materiales.ajustar-inventario');

    // Rutas de Órdenes de Trabajo
    Route::resource('ordenes', OrdenTrabajoController::class);

    // Rutas de Mantenimiento Preventivo
    Route::resource('mantenimiento', MantenimientoPreventivoController::class);
    Route::post('/mantenimiento/generar-ordenes', [MantenimientoPreventivoController::class, 'generarOrdenes'])
        ->name('mantenimiento.generar-ordenes');

    // Rutas de Presupuestos
    Route::resource('presupuestos', PresupuestoController::class);
    Route::get('/presupuestos/export/pdf', [PresupuestoController::class, 'exportarPDF'])
        ->name('presupuestos.export.pdf');
    Route::get('/presupuestos/export/excel', [PresupuestoController::class, 'exportarExcel'])
        ->name('presupuestos.export.excel');

    // Rutas de Rutas
    Route::resource('rutas', RutaController::class);
    Route::post('/rutas/{ruta}/iniciar', [RutaController::class, 'iniciarRuta'])
        ->name('rutas.iniciar');
    Route::post('/rutas/{ruta}/finalizar', [RutaController::class, 'finalizarRuta'])
        ->name('rutas.finalizar');

    // Rutas de Exportación
    Route::get('/export/incidencias/{format}', [ExportController::class, 'incidencias'])
        ->name('export.incidencias')
        ->where('format', 'pdf|excel');
});

require __DIR__.'/auth.php';
