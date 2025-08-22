<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('examen_medicos', function (Blueprint $table) {
            $table->foreignId('empresa_id')
                  ->after('id')
                  ->constrained()       // asume tabla empresas
                  ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('examen_medicos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('empresa_id');
        });
    }
};
