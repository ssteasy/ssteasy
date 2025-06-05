<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreguntasToCapacitacionSesionesTable extends Migration
{
    public function up()
    {
        Schema::table('capacitacion_sesiones', function (Blueprint $table) {
            $table->json('preguntas')
                  ->nullable()
                  ->after('requiere_aprobacion');
        });
    }

    public function down()
    {
        Schema::table('capacitacion_sesiones', function (Blueprint $table) {
            $table->dropColumn('preguntas');
        });
    }
}
