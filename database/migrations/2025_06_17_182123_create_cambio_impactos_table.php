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
        Schema::create('cambio_impactos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gestion_cambio_id')->constrained()->cascadeOnDelete();
            $table->string('peligro_riesgo', 255)->nullable();
            $table->string('requisitos_legales', 255)->nullable();
            $table->string('sistema_gestion', 255)->nullable();
            $table->string('procedimiento', 255)->nullable();
            $table->string('otros', 255)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cambio_impactos');
    }
};
