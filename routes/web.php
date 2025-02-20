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
use App\Http\Controllers\MantenimientoPredictivoController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\RecursoController;
use App\Mail\NotificacionIncidencia;
use Illuminate\Support\Facades\Mail;
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
    Route::get('infraestructura-mapa', [InfraestructuraController::class, 'mapa'])
        ->name('infraestructura.mapa');

    // Rutas de Incidencias
    Route::resource('incidencias', IncidenciaController::class);

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

    // Rutas para gestión de recursos
    Route::prefix('recursos')->middleware(['auth'])->group(function () {
        Route::get('/personal', [RecursoController::class, 'indexPersonal'])->name('recursos.personal.index');
        Route::get('/materiales', [RecursoController::class, 'indexMateriales'])->name('recursos.materiales.index');
        Route::post('/orden-trabajo/{ordenTrabajo}/asignar', [RecursoController::class, 'asignarRecursos'])->name('recursos.asignar');
        Route::get('/optimizar-rutas', [RecursoController::class, 'optimizarRutas'])->name('recursos.optimizar-rutas');
    });

    // Rutas para análisis predictivo
    Route::prefix('analisis')->middleware(['auth', 'role:admin,supervisor'])->group(function () {
        Route::get('/predictivo', [MantenimientoPredictivoController::class, 'analizarPatrones'])
            ->name('analisis.predictivo');
    });

    // Ruta de prueba para correo
    Route::get('/test-email', function () {
        $incidencia = \App\Models\Incidencia::first();
        
        if (!$incidencia) {
            return 'No hay incidencias para probar. Crea una primero.';
        }

        Mail::to('test@example.com')->send(new NotificacionIncidencia($incidencia));
        return 'Correo enviado! Revisa Mailtrap.';
    });
});

require __DIR__.'/auth.php';
