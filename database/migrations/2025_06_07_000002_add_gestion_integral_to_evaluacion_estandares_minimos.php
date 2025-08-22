<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGestionIntegralToEvaluacionEstandaresMinimos extends Migration
{
    public function up()
    {
        Schema::table('evaluacion_estandares_minimos', function (Blueprint $table) {
            //
            // — Campos de estado (3-estados) como TEXT
            //
            $table->text('planear_gestion_integral_politica_sst')->nullable();
            $table->text('planear_gestion_integral_objetivos_sst')->nullable();
            $table->text('planear_gestion_integral_evaluacion_prioridades')->nullable();
            $table->text('planear_gestion_integral_plan_objetivos')->nullable();
            $table->text('planear_gestion_integral_retencion_documental')->nullable();
            $table->text('planear_gestion_integral_rendicion_desempeno')->nullable();
            $table->text('planear_gestion_integral_matriz_legal')->nullable();
            $table->text('planear_gestion_integral_comunicacion_sst')->nullable();
            $table->text('planear_gestion_integral_adquisicion_productos')->nullable();
            $table->text('planear_gestion_integral_evaluacion_proveedores')->nullable();
            $table->text('planear_gestion_integral_impacto_cambios')->nullable();

            //
            // — Campos de adjunto también como TEXT
            //
            foreach ([
                'politica_sst',
                'objetivos_sst',
                'evaluacion_prioridades',
                'plan_objetivos',
                'retencion_documental',
                'rendicion_desempeno',
                'matriz_legal',
                'comunicacion_sst',
                'adquisicion_productos',
                'evaluacion_proveedores',
                'impacto_cambios',
            ] as $suffix) {
                $table->text("planear_gestion_integral_{$suffix}_archivo")->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('evaluacion_estandares_minimos', function (Blueprint $table) {
            $table->dropColumn([
                'planear_gestion_integral_politica_sst',
                'planear_gestion_integral_objetivos_sst',
                'planear_gestion_integral_evaluacion_prioridades',
                'planear_gestion_integral_plan_objetivos',
                'planear_gestion_integral_retencion_documental',
                'planear_gestion_integral_rendicion_desempeno',
                'planear_gestion_integral_matriz_legal',
                'planear_gestion_integral_comunicacion_sst',
                'planear_gestion_integral_adquisicion_productos',
                'planear_gestion_integral_evaluacion_proveedores',
                'planear_gestion_integral_impacto_cambios',
            ]);

            foreach ([
                'politica_sst',
                'objetivos_sst',
                'evaluacion_prioridades',
                'plan_objetivos',
                'retencion_documental',
                'rendicion_desempeno',
                'matriz_legal',
                'comunicacion_sst',
                'adquisicion_productos',
                'evaluacion_proveedores',
                'impacto_cambios',
            ] as $suffix) {
                $table->dropColumn("planear_gestion_integral_{$suffix}_archivo");
            }
        });
    }
}
