<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdjuntosToEvaluacionEstandaresMinimosTable extends Migration
{
    public function up()
    {
        Schema::table('evaluacion_estandares_minimos', function (Blueprint $table) {
            //
            // === “Planear” (10%) ⇒ cada booleano ya existe: aquí agregamos la columna string para el adjunto
            //
            $table->string('planear_recursos_responsable_sst_archivo')->nullable();
            $table->string('planear_recursos_responsabilidades_sst_archivo')->nullable();
            $table->string('planear_recursos_asignacion_recursos_archivo')->nullable();
            $table->string('planear_recursos_afiliacion_sg_riesgos_archivo')->nullable();
            $table->string('planear_recursos_pension_altoriesgo_archivo')->nullable();
            $table->string('planear_recursos_conformacion_copasst_archivo')->nullable();
            $table->string('planear_recursos_capacitacion_copasst_archivo')->nullable();
            $table->string('planear_recursos_conformacion_convivencia_archivo')->nullable();

            $table->string('planear_capacitacion_programa_pyP_archivo')->nullable();
            $table->string('planear_capacitacion_induccion_reinduccion_archivo')->nullable();
            $table->string('planear_capacitacion_responsable_curso50h_archivo')->nullable();

            //
            // === “Hacer” → Gestión de la Salud (20%)
            //
            $table->string('hacer_salud_descripcion_sociodemografica_archivo')->nullable();
            $table->string('hacer_salud_actividades_promocion_prevencion_archivo')->nullable();
            $table->string('hacer_salud_perfiles_cargo_info_medico_archivo')->nullable();
            $table->string('hacer_salud_examenes_ocupacionales_archivo')->nullable();
            $table->string('hacer_salud_custodia_historias_clinicas_archivo')->nullable();
            $table->string('hacer_salud_restricciones_recomendaciones_archivo')->nullable();
            $table->string('hacer_salud_estilos_vida_entornos_saludables_archivo')->nullable();
            $table->string('hacer_salud_agua_potable_sanitarios_archivo')->nullable();
            $table->string('hacer_salud_eliminacion_residuos_archivo')->nullable();

            $table->string('hacer_salud_reporte_accidentes_enfermedades_archivo')->nullable();
            $table->string('hacer_salud_investigacion_accidentes_archivo')->nullable();
            $table->string('hacer_salud_registro_estadistico_archivo')->nullable();

            $table->string('hacer_salud_frecuencia_accidentalidad_archivo')->nullable();
            $table->string('hacer_salud_severidad_accidentalidad_archivo')->nullable();
            $table->string('hacer_salud_mortalidad_accidentes_archivo')->nullable();
            $table->string('hacer_salud_prevalencia_enfermedad_archivo')->nullable();
            $table->string('hacer_salud_incidencia_enfermedad_archivo')->nullable();
            $table->string('hacer_salud_ausentismo_causa_medica_archivo')->nullable();

            //
            // === “Hacer” → Gestión de Peligros y Riesgos (30%)
            //
            $table->string('hacer_riesgos_metodologia_identificacion_archivo')->nullable();
            $table->string('hacer_riesgos_identificacion_participacion_archivo')->nullable();
            $table->string('hacer_riesgos_identificacion_sustancias_cancerigenas_archivo')->nullable();
            $table->string('hacer_riesgos_mediciones_ambientales_archivo')->nullable();

            $table->string('hacer_riesgos_medidas_prevencion_control_archivo')->nullable();
            $table->string('hacer_riesgos_verificacion_trabajadores_archivo')->nullable();
            $table->string('hacer_riesgos_elaboracion_procedimientos_archivo')->nullable();
            $table->string('hacer_riesgos_inspecciones_copasst_archivo')->nullable();
            $table->string('hacer_riesgos_mantenimiento_periodico_archivo')->nullable();
            $table->string('hacer_riesgos_entrega_epp_archivo')->nullable();

            //
            // === “Hacer” → Gestión de Amenazas (10%)
            //
            $table->string('hacer_amenazas_plan_emergencia_archivo')->nullable();
            $table->string('hacer_amenazas_brigada_emergencias_archivo')->nullable();

            //
            // === “Verificar” (5%)
            //
            $table->string('verificar_indicadores_sst_archivo')->nullable();
            $table->string('verificar_auditoria_anual_archivo')->nullable();
            $table->string('verificar_revision_alta_direccion_archivo')->nullable();
            $table->string('verificar_planificacion_copasst_archivo')->nullable();

            //
            // === “Actuar” (10%)
            //
            $table->string('actuar_acciones_prev_y_corr_archivo')->nullable();
            $table->string('actuar_mejoras_revision_alta_direccion_archivo')->nullable();
            $table->string('actuar_mejoras_investigacion_archivo')->nullable();
            $table->string('actuar_plan_mejoramiento_archivo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('evaluacion_estandares_minimos', function (Blueprint $table) {
            //
            // Eliminamos todas las columnas que agregamos en up()
            //
            $table->dropColumn([
                'planear_recursos_responsable_sst_archivo',
                'planear_recursos_responsabilidades_sst_archivo',
                'planear_recursos_asignacion_recursos_archivo',
                'planear_recursos_afiliacion_sg_riesgos_archivo',
                'planear_recursos_pension_altoriesgo_archivo',
                'planear_recursos_conformacion_copasst_archivo',
                'planear_recursos_capacitacion_copasst_archivo',
                'planear_recursos_conformacion_convivencia_archivo',

                'planear_capacitacion_programa_pyP_archivo',
                'planear_capacitacion_induccion_reinduccion_archivo',
                'planear_capacitacion_responsable_curso50h_archivo',

                'hacer_salud_descripcion_sociodemografica_archivo',
                'hacer_salud_actividades_promocion_prevencion_archivo',
                'hacer_salud_perfiles_cargo_info_medico_archivo',
                'hacer_salud_examenes_ocupacionales_archivo',
                'hacer_salud_custodia_historias_clinicas_archivo',
                'hacer_salud_restricciones_recomendaciones_archivo',
                'hacer_salud_estilos_vida_entornos_saludables_archivo',
                'hacer_salud_agua_potable_sanitarios_archivo',
                'hacer_salud_eliminacion_residuos_archivo',

                'hacer_salud_reporte_accidentes_enfermedades_archivo',
                'hacer_salud_investigacion_accidentes_archivo',
                'hacer_salud_registro_estadistico_archivo',

                'hacer_salud_frecuencia_accidentalidad_archivo',
                'hacer_salud_severidad_accidentalidad_archivo',
                'hacer_salud_mortalidad_accidentes_archivo',
                'hacer_salud_prevalencia_enfermedad_archivo',
                'hacer_salud_incidencia_enfermedad_archivo',
                'hacer_salud_ausentismo_causa_medica_archivo',

                'hacer_riesgos_metodologia_identificacion_archivo',
                'hacer_riesgos_identificacion_participacion_archivo',
                'hacer_riesgos_identificacion_sustancias_cancerigenas_archivo',
                'hacer_riesgos_mediciones_ambientales_archivo',

                'hacer_riesgos_medidas_prevencion_control_archivo',
                'hacer_riesgos_verificacion_trabajadores_archivo',
                'hacer_riesgos_elaboracion_procedimientos_archivo',
                'hacer_riesgos_inspecciones_copasst_archivo',
                'hacer_riesgos_mantenimiento_periodico_archivo',
                'hacer_riesgos_entrega_epp_archivo',

                'hacer_amenazas_plan_emergencia_archivo',
                'hacer_amenazas_brigada_emergencias_archivo',

                'verificar_indicadores_sst_archivo',
                'verificar_auditoria_anual_archivo',
                'verificar_revision_alta_direccion_archivo',
                'verificar_planificacion_copasst_archivo',

                'actuar_acciones_prev_y_corr_archivo',
                'actuar_mejoras_revision_alta_direccion_archivo',
                'actuar_mejoras_investigacion_archivo',
                'actuar_plan_mejoramiento_archivo',
            ]);
        });
    }
}
