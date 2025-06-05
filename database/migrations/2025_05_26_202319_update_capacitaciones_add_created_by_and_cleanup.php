<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capacitaciones', function (Blueprint $t) {
            if (!Schema::hasColumn('capacitaciones', 'created_by')) {
                $t->foreignId('created_by')
                  ->after('empresa_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            }

            /* Quita columnas obsoletas solo si realmente no las usarás */
            if (Schema::hasColumn('capacitaciones', 'codigo_plan'))  $t->dropColumn('codigo_plan');
            if (Schema::hasColumn('capacitaciones', 'nombre_plan'))  $t->dropColumn('nombre_plan');
        });
    }

    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $t) {
            $t->dropConstrainedForeignId('created_by');
            /* Re-créalo solo si lo eliminaste arriba */
            // $t->string('codigo_plan')->nullable();
            // $t->string('nombre_plan')->nullable();
        });
    }
};
