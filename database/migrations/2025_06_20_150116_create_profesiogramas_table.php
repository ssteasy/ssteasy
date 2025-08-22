<?php

// database/migrations/xxxx_xx_xx_create_profesiogramas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfesiogramasTable extends Migration
{
    public function up()
    {
        Schema::create('profesiogramas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cargo_id')->constrained('cargos'); 
            $table->text('tareas');
            $table->text('funciones');
            $table->string('periodicidad_examenes')->nullable();
            $table->text('vacunas')->nullable();
            $table->text('riesgos')->nullable();
            $table->text('epp')->nullable();
            $table->json('adjuntos')->nullable(); // almacenará rutas a PDFs/Word
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profesiogramas');
    }
}
