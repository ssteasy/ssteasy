<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capacitacion_sesiones', function (Blueprint $table) {
            // 1) Añadir miniatura (imagen opcional)
            $table->string('miniatura')->nullable()->after('contenido_html');

            // 2) Eliminar campo ya no usado
            $table->dropColumn('requiere_aprobacion');

            // 3) Asegurar tipo LONGTEXT (si fuera necesario)
            $table->longText('contenido_html')->change();
        });
    }

    public function down(): void
    {
        Schema::table('capacitacion_sesiones', function (Blueprint $table) {
            // Para revertir:
            $table->dropColumn('miniatura');

            $table->tinyInteger('requiere_aprobacion')
                  ->default(0)
                  ->after('prerequisite_id');

            $table->text('contenido_html')->change();
        });
    }
};
