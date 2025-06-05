<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            // 1) miniatura
            $table->string('miniatura')->nullable()->after('empresa_id');

            // 2+3) fechas opcionales
            $table->date('fecha_inicio')->nullable()->change();
            $table->date('fecha_fin')   ->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->dropColumn('miniatura');
            $table->date('fecha_inicio')->nullable(false)->change();
            $table->date('fecha_fin')   ->nullable(false)->change();
        });
    }
};

