<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEppCargoTable extends Migration
{
    public function up()
    {
        Schema::table('epp_cargo', function (Blueprint $table) {
            // Foreign keys
            $table->foreignId('epp_id')
                  ->after('id')
                  ->constrained('epps')
                  ->cascadeOnDelete();
            $table->foreignId('cargo_id')
                  ->after('epp_id')
                  ->constrained('cargos')
                  ->cascadeOnDelete();

            // Tus campos extra
            $table->unsignedInteger('periodicidad_valor')
                  ->nullable()
                  ->after('cargo_id');
            $table->string('periodicidad_unidad', 20)
                  ->nullable()
                  ->after('periodicidad_valor'); // días|meses|años

            $table->unsignedInteger('cantidad')
                  ->nullable()
                  ->after('periodicidad_unidad');

            $table->unsignedInteger('reposicion_valor')
                  ->nullable()
                  ->after('cantidad');
            $table->string('reposicion_unidad', 20)
                  ->nullable()
                  ->after('reposicion_valor');  // días|semanas
        });
    }

    public function down()
    {
        Schema::table('epp_cargo', function (Blueprint $table) {
            $table->dropConstrainedForeignId('epp_id');
            $table->dropConstrainedForeignId('cargo_id');
            $table->dropColumn([
                'periodicidad_valor',
                'periodicidad_unidad',
                'cantidad',
                'reposicion_valor',
                'reposicion_unidad',
            ]);
        });
    }
}
