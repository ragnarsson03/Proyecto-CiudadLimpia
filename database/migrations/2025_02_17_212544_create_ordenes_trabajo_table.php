<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('incidencia_id')->nullable()->constrained();
            $table->foreignId('infraestructura_id')->constrained();
            $table->enum('tipo', ['correctivo', 'preventivo']);
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'cancelada']);
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica']);
            $table->text('descripcion');
            $table->timestamp('fecha_programada');
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->decimal('costo_estimado', 10, 2)->nullable();
            $table->decimal('costo_real', 10, 2)->nullable();
            $table->json('materiales_requeridos')->nullable();
            $table->json('personal_asignado')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};
