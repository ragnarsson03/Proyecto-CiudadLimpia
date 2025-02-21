<?php

use App\Http\Controllers\{
    ProfileController,
    InfraestructuraController,
    IncidenciaController,
    DashboardController,
    ExportController,
    PersonalController,
    MaterialController,
    OrdenTrabajoController,
    MantenimientoPreventivoController,
    MantenimientoPredictivoController,
    PresupuestoController,
    RutaController,
    RecursoController,
    MapController,
    IncidentController,
};
use App\Mail\NotificacionIncidencia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Ruta principal
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard y perfil
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Infraestructura y mapa
    Route::resource('infraestructura', InfraestructuraController::class);
    Route::get('infraestructura-mapa', [InfraestructuraController::class, 'mapa'])
        ->name('infraestructura.mapa');
    Route::get('/map', [MapController::class, 'index'])->name('map');

    // Incidencias
    Route::resource('incidents', IncidentController::class);
    Route::post('incidents/{incident}/status', [IncidentController::class, 'updateStatus'])->name('incidents.updateStatus');
    Route::get('incidents-map', [IncidenciaController::class, 'map'])->name('incidents.map');
    Route::get('/incidents/export/excel', [IncidenciaController::class, 'exportExcel'])->name('incidents.excel');
    Route::get('/incidents/export/pdf', [IncidenciaController::class, 'exportPDF'])->name('incidents.pdf');

    // Personal y Materiales
    Route::resource('personal', PersonalController::class);
    Route::resource('materiales', MaterialController::class);
    Route::post('/materiales/{material}/ajustar-inventario', [MaterialController::class, 'ajustarInventario'])
        ->name('materiales.ajustar-inventario');

    // Órdenes de trabajo y mantenimiento
    Route::resource('ordenes', OrdenTrabajoController::class);
    Route::resource('mantenimiento', MantenimientoPreventivoController::class);
    Route::post('/mantenimiento/generar-ordenes', [MantenimientoPreventivoController::class, 'generarOrdenes'])
        ->name('mantenimiento.generar-ordenes');

    // Presupuestos
    Route::resource('presupuestos', PresupuestoController::class);
    Route::get('/presupuestos/export/pdf', [PresupuestoController::class, 'exportarPDF'])
        ->name('presupuestos.export.pdf');
    Route::get('/presupuestos/export/excel', [PresupuestoController::class, 'exportarExcel'])
        ->name('presupuestos.export.excel');

    // Rutas y recursos
    Route::resource('rutas', RutaController::class);
    Route::post('/rutas/{ruta}/iniciar', [RutaController::class, 'iniciarRuta'])->name('rutas.iniciar');
    Route::post('/rutas/{ruta}/finalizar', [RutaController::class, 'finalizarRuta'])->name('rutas.finalizar');

    // Gestión de recursos
    Route::prefix('recursos')->group(function () {
        Route::get('/personal', [RecursoController::class, 'indexPersonal'])->name('recursos.personal.index');
        Route::get('/materiales', [RecursoController::class, 'indexMateriales'])->name('recursos.materiales.index');
        Route::post('/orden-trabajo/{ordenTrabajo}/asignar', [RecursoController::class, 'asignarRecursos'])
            ->name('recursos.asignar');
        Route::get('/optimizar-rutas', [RecursoController::class, 'optimizarRutas'])
            ->name('recursos.optimizar-rutas');
    });

    // Análisis predictivo (solo admin y supervisor)
    Route::prefix('analisis')->middleware('role:admin,supervisor')->group(function () {
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
