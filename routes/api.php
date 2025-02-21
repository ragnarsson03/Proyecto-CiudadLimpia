<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\IncidentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Rutas para ciudadanos
    Route::post('/incidencias', [ApiController::class, 'reportarIncidencia']);
    Route::get('/incidencias', [ApiController::class, 'consultarIncidencias']);
    Route::get('/infraestructuras', [ApiController::class, 'consultarInfraestructuras']);
    Route::get('/estadisticas', [ApiController::class, 'obtenerEstadisticas']);
    Route::get('/incidents', [IncidentController::class, 'index']);
});
