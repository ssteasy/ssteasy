<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEvaluacionEstandaresMinimosThreeState extends Migration
{
    public function up()
    {
        // NOTA: ya no usamos enum()->change(), sino string()->default('no_cumple')->change().
        Schema::table('evaluacion_estandares_minimos', function (Blueprint $table) {
            //
            // === “PLANEAR” (antes booleanos) ⇒ ahora string(20) con default 'no_cumple'
            //
            $table->string('planear_recursos_responsable_sst', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_recursos_responsabilidades_sst', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_recursos_asignacion_recursos', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_recursos_afiliacion_sg_riesgos', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_recursos_pension_altoriesgo', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_recursos_conformacion_copasst', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_recursos_capacitacion_copasst', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_recursos_conformacion_convivencia', 20)
                  ->default('no_cumple')
                  ->change();

            $table->string('planear_capacitacion_programa_pyP', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_capacitacion_induccion_reinduccion', 20)
                  ->default('no_cumple')
                  ->change();
            $table->string('planear_capacitacion_responsable_curso50h', 20)
                  ->default('no_cumple')
                  ->change();

            //
            // === HACER → Gestión de la Salud (antes booleanos)
            //
            $table->string('hacer_salud_descripcion_sociodemografica', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_actividades_promocion_prevencion', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_perfiles_cargo_info_medico', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_examenes_ocupacionales', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_custodia_historias_clinicas', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_restricciones_recomendaciones', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_estilos_vida_entornos_saludables', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_agua_potable_sanitarios', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_eliminacion_residuos', 20)
                  ->default('no_cumple')->change();

            $table->string('hacer_salud_reporte_accidentes_enfermedades', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_investigacion_accidentes', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_registro_estadistico', 20)
                  ->default('no_cumple')->change();

            $table->string('hacer_salud_frecuencia_accidentalidad', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_severidad_accidentalidad', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_mortalidad_accidentes', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_prevalencia_enfermedad', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_incidencia_enfermedad', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_salud_ausentismo_causa_medica', 20)
                  ->default('no_cumple')->change();

            //
            // === HACER → Gestión de Peligros y Riesgos (antes booleanos)
            //
            $table->string('hacer_riesgos_metodologia_identificacion', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_identificacion_participacion', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_identificacion_sustancias_cancerigenas', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_mediciones_ambientales', 20)
                  ->default('no_cumple')->change();

            $table->string('hacer_riesgos_medidas_prevencion_control', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_verificacion_trabajadores', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_elaboracion_procedimientos', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_inspecciones_copasst', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_mantenimiento_periodico', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_riesgos_entrega_epp', 20)
                  ->default('no_cumple')->change();

            //
            // === HACER → Gestión de Amenazas (antes booleanos)
            //
            $table->string('hacer_amenazas_plan_emergencia', 20)
                  ->default('no_cumple')->change();
            $table->string('hacer_amenazas_brigada_emergencias', 20)
                  ->default('no_cumple')->change();

            //
            // === VERIFICAR (antes booleanos)
            //
            $table->string('verificar_indicadores_sst', 20)
                  ->default('no_cumple')->change();
            $table->string('verificar_auditoria_anual', 20)
                  ->default('no_cumple')->change();
            $table->string('verificar_revision_alta_direccion', 20)
                  ->default('no_cumple')->change();
            $table->string('verificar_planificacion_copasst', 20)
                  ->default('no_cumple')->change();

            //
            // === ACTUAR (antes booleanos)
            //
            $table->string('actuar_acciones_prev_y_corr', 20)
                  ->default('no_cumple')->change();
            $table->string('actuar_mejoras_revision_alta_direccion', 20)
                  ->default('no_cumple')->change();
            $table->string('actuar_mejoras_investigacion', 20)
                  ->default('no_cumple')->change();
            $table->string('actuar_plan_mejoramiento', 20)
                  ->default('no_cumple')->change();
        });
    }

    public function down()
    {
        Schema::table('evaluacion_estandares_minimos', function (Blueprint $table) {
            //
            // Para revertir, volvemos a booleano default(false)
            //
            $table->boolean('planear_recursos_responsable_sst')->default(false)->change();
            $table->boolean('planear_recursos_responsabilidades_sst')->default(false)->change();
            $table->boolean('planear_recursos_asignacion_recursos')->default(false)->change();
            $table->boolean('planear_recursos_afiliacion_sg_riesgos')->default(false)->change();
            $table->boolean('planear_recursos_pension_altoriesgo')->default(false)->change();
            $table->boolean('planear_recursos_conformacion_copasst')->default(false)->change();
            $table->boolean('planear_recursos_capacitacion_copasst')->default(false)->change();
            $table->boolean('planear_recursos_conformacion_convivencia')->default(false)->change();

            $table->boolean('planear_capacitacion_programa_pyP')->default(false)->change();
            $table->boolean('planear_capacitacion_induccion_reinduccion')->default(false)->change();
            $table->boolean('planear_capacitacion_responsable_curso50h')->default(false)->change();

            $table->boolean('hacer_salud_descripcion_sociodemografica')->default(false)->change();
            $table->boolean('hacer_salud_actividades_promocion_prevencion')->default(false)->change();
            $table->boolean('hacer_salud_perfiles_cargo_info_medico')->default(false)->change();
            $table->boolean('hacer_salud_examenes_ocupacionales')->default(false)->change();
            $table->boolean('hacer_salud_custodia_historias_clinicas')->default(false)->change();
            $table->boolean('hacer_salud_restricciones_recomendaciones')->default(false)->change();
            $table->boolean('hacer_salud_estilos_vida_entornos_saludables')->default(false)->change();
            $table->boolean('hacer_salud_agua_potable_sanitarios')->default(false)->change();
            $table->boolean('hacer_salud_eliminacion_residuos')->default(false)->change();

            $table->boolean('hacer_salud_reporte_accidentes_enfermedades')->default(false)->change();
            $table->boolean('hacer_salud_investigacion_accidentes')->default(false)->change();
            $table->boolean('hacer_salud_registro_estadistico')->default(false)->change();

            $table->boolean('hacer_salud_frecuencia_accidentalidad')->default(false)->change();
            $table->boolean('hacer_salud_severidad_accidentalidad')->default(false)->change();
            $table->boolean('hacer_salud_mortalidad_accidentes')->default(false)->change();
            $table->boolean('hacer_salud_prevalencia_enfermedad')->default(false)->change();
            $table->boolean('hacer_salud_incidencia_enfermedad')->default(false)->change();
            $table->boolean('hacer_salud_ausentismo_causa_medica')->default(false)->change();

            $table->boolean('hacer_riesgos_metodologia_identificacion')->default(false)->change();
            $table->boolean('hacer_riesgos_identificacion_participacion')->default(false)->change();
            $table->boolean('hacer_riesgos_identificacion_sustancias_cancerigenas')->default(false)->change();
            $table->boolean('hacer_riesgos_mediciones_ambientales')->default(false)->change();

            $table->boolean('hacer_riesgos_medidas_prevencion_control')->default(false)->change();
            $table->boolean('hacer_riesgos_verificacion_trabajadores')->default(false)->change();
            $table->boolean('hacer_riesgos_elaboracion_procedimientos')->default(false)->change();
            $table->boolean('hacer_riesgos_inspecciones_copasst')->default(false)->change();
            $table->boolean('hacer_riesgos_mantenimiento_periodico')->default(false)->change();
            $table->boolean('hacer_riesgos_entrega_epp')->default(false)->change();

            $table->boolean('hacer_amenazas_plan_emergencia')->default(false)->change();
            $table->boolean('hacer_amenazas_brigada_emergencias')->default(false)->change();

            $table->boolean('verificar_indicadores_sst')->default(false)->change();
            $table->boolean('verificar_auditoria_anual')->default(false)->change();
            $table->boolean('verificar_revision_alta_direccion')->default(false)->change();
            $table->boolean('verificar_planificacion_copasst')->default(false)->change();

            $table->boolean('actuar_acciones_prev_y_corr')->default(false)->change();
            $table->boolean('actuar_mejoras_revision_alta_direccion')->default(false)->change();
            $table->boolean('actuar_mejoras_investigacion')->default(false)->change();
            $table->boolean('actuar_plan_mejoramiento')->default(false)->change();
        });
    }
}
