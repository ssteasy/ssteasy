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
// database/migrations/xxxx_xx_xx_create_municipios_table.php
Schema::create('municipios', function (Blueprint $table) {
    $table->string('codigo', 10)->primary();    // código DANE completo
    $table->string('nombre');
    $table->string('departamento_codigo', 10);
    $table->foreign('departamento_codigo')->references('codigo')->on('departamentos')->cascadeOnDelete();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
