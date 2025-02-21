<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->enum('priority', ['Baja', 'Media', 'Alta', 'Crítica'])->default('Media');
        });

        // Add the computed column using raw SQL for PostgreSQL
        DB::statement("
            ALTER TABLE incidents ADD COLUMN priority_color VARCHAR(255) GENERATED ALWAYS AS (
                CASE 
                    WHEN priority = 'Baja' THEN 'success'
                    WHEN priority = 'Media' THEN 'warning'
                    WHEN priority = 'Alta' THEN 'danger'
                    WHEN priority = 'Crítica' THEN 'dark'
                    ELSE 'primary'
                END
            ) STORED
        ");
    }

    public function down()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn('priority_color');
            $table->dropColumn('priority');
        });
    }
};