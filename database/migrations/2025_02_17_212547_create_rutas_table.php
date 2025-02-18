<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->constrained('personal');
            $table->date('fecha');
            $table->json('ordenes_trabajo')->comment('Array de IDs de órdenes de trabajo');
            $table->json('puntos')->comment('Array de coordenadas ordenadas');
            $table->decimal('distancia_total', 8, 2)->comment('en kilómetros');
            $table->integer('tiempo_estimado')->comment('en minutos');
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'cancelada']);
            $table->timestamp('hora_inicio')->nullable();
            $table->timestamp('hora_fin')->nullable();
            $table->json('metricas')->nullable()->comment('Tiempo real, distancia real, etc.');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutas');
    }
};
