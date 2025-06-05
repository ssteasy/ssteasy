<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up()
    {
        Schema::create('rols', function (Blueprint $table) {
            $table->id();
            // FK a empresas (siempre que tengas tabla 'empresas')
            $table->foreignId('empresa_id')
                  ->constrained()
                  ->onDelete('cascade');
            // FK a cargos
            $table->foreignId('cargo_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rols');
    }
};
