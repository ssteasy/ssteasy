<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('encuestas', function (Blueprint $table) {
            // JSON para guardar cada pregunta como:
            // [{ "enunciado":"¿…?","tipo":"opcion_multiple","opciones":["A","B","C"] }, …]
            $table->json('preguntas')->nullable()->after('activa');
        });
    }

    public function down(): void
    {
        Schema::table('encuestas', function (Blueprint $table) {
            $table->dropColumn('preguntas');
        });
    }
};
