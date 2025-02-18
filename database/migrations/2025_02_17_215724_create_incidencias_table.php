<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('ubicacion');
            $table->text('descripcion');
            $table->timestamp('fecha');
            $table->enum('estado', ['pendiente', 'en_proceso', 'resuelto', 'cancelado']);
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica']);
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->foreignId('infraestructura_id')->constrained('infraestructuras')->onDelete('cascade');
            $table->foreignId('tecnico_id')->nullable()->constrained('users');
            $table->foreignId('ciudadano_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidencias');
    }
};
