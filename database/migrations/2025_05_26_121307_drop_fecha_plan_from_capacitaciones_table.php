<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Quitar la columna.
     */
    public function up(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->dropColumn('fecha_plan');
        });
    }

    /**
     * Revertir la operación (opcional).
     */
    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            // Ajusta el tipo según el que usaras originalmente.
            $table->date('fecha_plan')->nullable();
        });
    }
};
