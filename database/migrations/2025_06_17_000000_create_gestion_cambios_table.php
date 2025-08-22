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
        Schema::create('gestion_cambios', function (Blueprint $table) {
            $table->id();                                // Código
            $table->date('fecha');                       // Fecha solicitud
            $table->string('descripcion_cambio', 255);
            $table->string('analisis_riesgo', 255)->nullable();
            $table->string('requisitos_legales', 255)->nullable();
            $table->string('requerimientos_sst', 255)->nullable();
            $table->text('analisis_impacto_sst')->nullable();
            $table->enum('estado', ['planificado','en_ejecucion','ejecutado'])
                  ->default('planificado');
        
            // Multi-empresa
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
        
            // Auditoría
            $table->foreignId('creado_por')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestion_cambios');
    }
};
