<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidenciasTable extends Migration
{
    public function up()
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('ubicacion');
            $table->text('descripcion');
            $table->timestamp('fecha');
            $table->string('estado')->default('pendiente');
            $table->string('prioridad');
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->foreignId('infraestructura_id')->constrained();
            $table->foreignId('tecnico_id')->nullable()->constrained('users');
            $table->foreignId('ciudadano_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidencias');
    }
}
