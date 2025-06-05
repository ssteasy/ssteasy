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
        Schema::create('sesion_user', function (Blueprint $t) {
            $t->id();
            $t->foreignId('sesion_id')->constrained('capacitacion_sesiones')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained();
            $t->timestamp('completado_at')->nullable();
            $t->unsignedTinyInteger('score')->nullable(); // 0-100 si hay cuestionario
            $t->boolean('aprobado')->nullable();
            $t->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_user');
    }
};
