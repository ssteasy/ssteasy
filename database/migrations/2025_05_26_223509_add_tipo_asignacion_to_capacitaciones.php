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
        Schema::table('capacitaciones', function (Blueprint $t) {
            $t->enum('tipo_asignacion', ['manual', 'abierta', 'obligatoria'])
              ->default('manual')
              ->after('activa');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            //
        });
    }
};
