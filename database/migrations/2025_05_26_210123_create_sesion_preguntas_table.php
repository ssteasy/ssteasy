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
        Schema::create('sesion_preguntas', function (Blueprint $t) {
            $t->id();
            $t->foreignId('sesion_id')->constrained('capacitacion_sesiones')->cascadeOnDelete();
            $t->enum('tipo', ['unica', 'multiple', 'vf', 'abierta']);
            $t->text('enunciado');
            $t->unsignedInteger('peso')->default(1);   // para ponderar la nota
            $t->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_preguntas');
    }
};
