<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfraestructurasTable extends Migration
{
    public function up()
    {
        Schema::create('infraestructuras', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('ubicacion');
            $table->text('descripcion');
            $table->string('estado')->default('operativo');
            $table->timestamp('ultima_revision')->nullable();
            $table->json('historial_mantenimiento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('infraestructuras');
    }
}
