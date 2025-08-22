<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncuestaUserTable extends Migration
{
    public function up()
    {
        Schema::create('encuesta_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encuesta_id')->constrained('encuestas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('encuesta_user');
    }
}
