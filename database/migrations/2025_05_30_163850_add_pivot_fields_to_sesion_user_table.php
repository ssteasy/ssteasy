<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // RESPUESTA JSON
        if (! Schema::hasColumn('sesion_user', 'respuesta_json')) {
            Schema::table('sesion_user', function (Blueprint $table) {
                $table->longText('respuesta_json')
                      ->nullable()
                      ->after('user_id');
            });
        }

        // SCORE
        if (! Schema::hasColumn('sesion_user', 'score')) {
            Schema::table('sesion_user', function (Blueprint $table) {
                $table->integer('score')
                      ->nullable()
                      ->after('respuesta_json');
            });
        }

        // APROBADO
        if (! Schema::hasColumn('sesion_user', 'aprobado')) {
            Schema::table('sesion_user', function (Blueprint $table) {
                $table->boolean('aprobado')
                      ->default(false)
                      ->after('score');
            });
        }
    }

    public function down(): void
    {
        Schema::table('sesion_user', function (Blueprint $table) {
            if (Schema::hasColumn('sesion_user', 'aprobado')) {
                $table->dropColumn('aprobado');
            }
            if (Schema::hasColumn('sesion_user', 'score')) {
                $table->dropColumn('score');
            }
            if (Schema::hasColumn('sesion_user', 'respuesta_json')) {
                $table->dropColumn('respuesta_json');
            }
        });
    }
};
