<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->string('nit', 20)->nullable();
            $table->string('actividad_economica')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->string('persona_contacto')->nullable();
            $table->string('foto')->nullable();               // ruta del archivo
            $table->text('google_maps_embed')->nullable();    // iframe
        });
    }

    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropColumn([
                'nit',
                'actividad_economica',
                'telefono',
                'direccion',
                'persona_contacto',
                'foto',
                'google_maps_embed',
            ]);
        });
    }
};
