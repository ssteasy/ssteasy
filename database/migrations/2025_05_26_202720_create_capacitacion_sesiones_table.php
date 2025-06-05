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
        Schema::create('capacitacion_sesiones', function (Blueprint $t) {
            $t->id();
            $t->foreignId('capacitacion_id')->constrained('capacitaciones')->cascadeOnDelete();
            $t->foreignId('created_by')->constrained('users');
            $t->string('titulo');
            $t->longText('contenido_html');                 // editor enriquecido
            $t->string('video_url')->nullable();
            $t->unsignedInteger('orden')->default(1);       // para bloquear avance secuencial
            $t->foreignId('prerequisite_id')               // sesión previa obligatoria
                ->nullable()
                ->constrained('capacitacion_sesiones')
                ->nullOnDelete();
            $t->boolean('requiere_aprobacion')->default(false); // si tiene preguntas abiertas
            $t->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacitacion_sesiones');
    }
};
