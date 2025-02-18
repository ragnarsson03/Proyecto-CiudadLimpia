<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mantenimiento_preventivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infraestructura_id')->constrained();
            $table->string('nombre');
            $table->text('descripcion');
            $table->enum('frecuencia', ['diaria', 'semanal', 'mensual', 'trimestral', 'semestral', 'anual']);
            $table->integer('dias_frecuencia');
            $table->date('ultima_ejecucion')->nullable();
            $table->date('proxima_ejecucion');
            $table->json('checklist')->nullable();
            $table->decimal('costo_estimado', 10, 2)->nullable();
            $table->integer('duracion_estimada')->comment('en minutos');
            $table->json('materiales_requeridos')->nullable();
            $table->json('personal_requerido')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mantenimiento_preventivo');
    }
};
