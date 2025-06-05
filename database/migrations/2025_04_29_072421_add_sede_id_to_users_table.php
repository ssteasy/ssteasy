<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Añadimos la columna (nullable para no romper insert actuales)
            $table->foreignId('sede_id')
                  ->nullable()
                  ->constrained('sedes')    // referencia a la tabla sedes
                  ->cascadeOnDelete()
                  ->after('empresa_id');     // posición después de empresa_id
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sede_id');
        });
    }
};
