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
        Schema::create('pregunta_opciones', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pregunta_id')->constrained('sesion_preguntas')->cascadeOnDelete();
            $t->string('texto');
            $t->boolean('es_correcta')->default(false);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregunta_opciones');
    }
};
