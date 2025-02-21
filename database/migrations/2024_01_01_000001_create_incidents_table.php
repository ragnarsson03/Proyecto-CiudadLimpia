<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('infrastructure_id')->constrained('infrastructures');
            $table->foreignId('status_id')->constrained('status');
            $table->string('priority');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla para el historial de cambios
        Schema::create('incident_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('status_id')->constrained('status');
            $table->text('comment');
            $table->timestamps();
        });

        // Tabla para las fotos de las incidencias
        Schema::create('incident_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_photos');
        Schema::dropIfExists('incident_history');
        Schema::dropIfExists('incidents');
    }
};

class Incident extends Model
{
    use SoftDeletes;
    // ... resto del código
}