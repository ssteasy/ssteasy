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
        Schema::create('capacitacion_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('capacitacion_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('estado', ['pendiente','en_progreso','finalizado'])->default('pendiente');
            $table->timestamp('fecha_finalizado')->nullable();
            $table->timestamps();
        
            $table->unique(['capacitacion_id','user_id']);
            $table->foreign('capacitacion_id')->references('id')->on('capacitaciones')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacitacion_user');
    }
};
