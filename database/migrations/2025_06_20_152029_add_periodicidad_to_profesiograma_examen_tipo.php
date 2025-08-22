<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profesiograma_examen_tipo', function (Blueprint $table) {
            $table->unsignedInteger('periodicidad_valor')->nullable()->after('examen_tipo_id');
            $table->string('periodicidad_unidad', 20)->nullable()->after('periodicidad_valor'); // 'días','meses','años'
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profesiograma_examen_tipo', function (Blueprint $table) {
            $table->dropColumn(['periodicidad_valor','periodicidad_unidad']);

        });
    }
};
