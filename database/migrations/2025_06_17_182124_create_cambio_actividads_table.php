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
        Schema::create('cambio_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gestion_cambio_id')->constrained()->cascadeOnDelete();
            $table->string('actividad', 255);
            $table->foreignId('responsable_id')->constrained('users');
            $table->foreignId('informar_a_id')->nullable()->constrained('users');
            $table->date('fecha_ejecucion');
            $table->date('fecha_seguimiento')->nullable();
            $table->foreignId('realizado_por')->constrained('users');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cambio_actividads');
    }
};
