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
        Schema::create('capacitaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');              // compañía dueña
            $table->string('codigo_plan');                         // ej. “2025-SST-01”
            $table->string('nombre_plan');
            $table->date('fecha_plan');
            $table->string('codigo_capacitacion')->unique();
            $table->string('nombre_capacitacion');
            $table->text('objetivo')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->json('sesiones')->nullable();                  // estructura flexible
            $table->boolean('activa')->default(true);
            $table->timestamps();
        
            $table->foreign('empresa_id')->references('id')->on('empresas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacitaciones');
    }
};
