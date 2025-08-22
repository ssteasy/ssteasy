<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('profesiograma_examen_tipo', function (Blueprint $table) {
        $table->string('tipificacion', 20)
              ->after('examen_tipo_id')
              ->comment('Ingreso, Egreso, Periódico, Post Incapacidad')
              ->default('Periódico');
    });
}

public function down()
{
    Schema::table('profesiograma_examen_tipo', function (Blueprint $table) {
        $table->dropColumn('tipificacion');
    });
}

};
