<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEppCargoTable extends Migration
{
    public function up()
    {
        Schema::create('epp_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epp_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cargo_id')->constrained()->cascadeOnDelete();
            // Campos extra que pediste:
            $table->unsignedInteger('periodicidad_valor')->nullable();
            $table->string('periodicidad_unidad', 20)->nullable();     // días|meses|años
            $table->unsignedInteger('cantidad')->nullable();            // cantidad de repuestos
            $table->unsignedInteger('reposicion_valor')->nullable();
            $table->string('reposicion_unidad', 20)->nullable();        // días|semanas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('epp_cargo');
    }
}

