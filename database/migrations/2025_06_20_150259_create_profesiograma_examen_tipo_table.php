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
        Schema::create('profesiograma_examen_tipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesiograma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('examen_tipo_id')->constrained('examen_tipos');
            $table->string('periodicidad')->nullable(); 
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesiograma_examen_tipo');
    }
};
