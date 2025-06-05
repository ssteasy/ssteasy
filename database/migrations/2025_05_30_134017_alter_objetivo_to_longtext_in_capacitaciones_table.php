<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            // Cambiamos objetivo de TEXT a LONGTEXT
            $table->longText('objetivo')->change();
        });
    }

    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            // Si quieres revertir, vuelve a TEXT (nota: truncará si hay >64KB)
            $table->text('objetivo')->change();
        });
    }
};
