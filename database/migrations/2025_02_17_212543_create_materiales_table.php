<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->integer('cantidad_disponible');
            $table->decimal('costo_unitario', 10, 2);
            $table->string('unidad_medida');
            $table->integer('stock_minimo');
            $table->integer('stock_maximo')->nullable();
            $table->string('ubicacion_almacen')->nullable();
            $table->string('codigo_interno')->unique();
            $table->json('proveedores')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materiales');
    }
};
