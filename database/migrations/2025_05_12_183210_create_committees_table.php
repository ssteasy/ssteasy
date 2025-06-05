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
       Schema::create('committees', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->text('objetivo')->nullable();
        $table->date('fecha_inicio_votaciones');
        $table->date('fecha_fin_votaciones');
        $table->date('fecha_inicio_inscripcion');
        $table->date('fecha_fin_inscripcion');
        $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
