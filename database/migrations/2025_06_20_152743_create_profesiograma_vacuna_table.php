<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('profesiograma_vacuna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesiograma_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('vacuna_id')
                  ->constrained('vacunas')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('profesiograma_vacuna');
    }
    
};
