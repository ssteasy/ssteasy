<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sgsst_responsables', function (Blueprint $table) {
            $table->id();
            // A qué usuario (empleado) referenciamos
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Fechas de responsabilidad
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            // Descripción de funciones
            $table->text('funciones');
            // Array de rutas a documentos (tarjeta profesional, licencia…)
            $table->json('documentos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sgsst_responsables');
    }
};
