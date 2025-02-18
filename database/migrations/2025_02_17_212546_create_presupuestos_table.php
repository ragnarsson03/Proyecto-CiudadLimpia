<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->year('año');
            $table->integer('mes');
            $table->decimal('monto_asignado', 12, 2);
            $table->decimal('monto_ejecutado', 12, 2)->default(0);
            $table->decimal('monto_comprometido', 12, 2)->default(0);
            $table->string('categoria');
            $table->string('zona')->nullable();
            $table->json('desglose')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['año', 'mes', 'categoria', 'zona']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('presupuestos');
    }
};
