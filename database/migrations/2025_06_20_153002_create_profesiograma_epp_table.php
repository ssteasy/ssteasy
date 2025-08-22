<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfesiogramaEppTable extends Migration
{
    public function up()
    {
        Schema::create('profesiograma_epp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesiograma_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('epp_id')
                  ->constrained('epps')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profesiograma_epp');
    }
}
