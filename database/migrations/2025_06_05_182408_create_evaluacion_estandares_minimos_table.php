<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluacionEstandaresMinimosTable extends Migration
{
    public function up()
    {
        Schema::create('evaluacion_estandares_minimos', function (Blueprint $table) {
            $table->id();

            // FK hacia empresas (asumo que existe tabla 'empresas' con campo 'id')
            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->onDelete('cascade');

            // Año de la evaluación
            $table->unsignedSmallInteger('year');

            //
            // === CAMPOS PARA “PLANEAR” (total 10%)
            //    1. Recursos (4%) → ocho sub-ítems de 0.5% cada uno
            //
            $table->boolean('planear_recursos_responsable_sst')->default(false);         // 1.1.1 (0.5%)
            $table->boolean('planear_recursos_responsabilidades_sst')->default(false);   // 1.1.2 (0.5%)
            $table->boolean('planear_recursos_asignacion_recursos')->default(false);      // 1.1.3 (0.5%)
            $table->boolean('planear_recursos_afiliacion_sg_riesgos')->default(false);    // 1.1.4 (0.5%)
            $table->boolean('planear_recursos_pension_altoriesgo')->default(false);       // 1.1.5 (0.5%)
            $table->boolean('planear_recursos_conformacion_copasst')->default(false);     // 1.1.6 (0.5%)
            $table->boolean('planear_recursos_capacitacion_copasst')->default(false);     // 1.1.7 (0.5%)
            $table->boolean('planear_recursos_conformacion_convivencia')->default(false); // 1.1.8 (0.5%)

            //    2. Capacitación en SG-SST (6%) → tres sub‐ítems de 2% cada uno
            $table->boolean('planear_capacitacion_programa_pyP')->default(false);      // 1.2.1 (2%)
            $table->boolean('planear_capacitacion_induccion_reinduccion')->default(false); // 1.2.2 (2%)
            $table->boolean('planear_capacitacion_responsable_curso50h')->default(false); // 1.2.3 (2%)

            //
            // === CAMPOS PARA “HACER” GESTIÓN DE LA SALUD (20%)
            //    1. Condiciones de salud (9%) → nueve sub‐ítems de 1% cada uno
            //
            $table->boolean('hacer_salud_descripcion_sociodemografica')->default(false);      // 3.1.1 (1%)
            $table->boolean('hacer_salud_actividades_promocion_prevencion')->default(false);  // 3.1.2 (1%)
            $table->boolean('hacer_salud_perfiles_cargo_info_medico')->default(false);        // 3.1.3 (1%)
            $table->boolean('hacer_salud_examenes_ocupacionales')->default(false);            // 3.1.4 (1%)
            $table->boolean('hacer_salud_custodia_historias_clinicas')->default(false);        // 3.1.5 (1%)
            $table->boolean('hacer_salud_restricciones_recomendaciones')->default(false);      // 3.1.6 (1%)
            $table->boolean('hacer_salud_estilos_vida_entornos_saludables')->default(false);   // 3.1.7 (1%)
            $table->boolean('hacer_salud_agua_potable_sanitarios')->default(false);           // 3.1.8 (1%)
            $table->boolean('hacer_salud_eliminacion_residuos')->default(false);              // 3.1.9 (1%)

            //    2. Registro/reporte/investigación accidentes (5%) → tres sub-ítems (2%, 2%, 1%)
            $table->boolean('hacer_salud_reporte_accidentes_enfermedades')->default(false); // 3.2.1 (2%)
            $table->boolean('hacer_salud_investigacion_accidentes')->default(false);         // 3.2.2 (2%)
            $table->boolean('hacer_salud_registro_estadistico')->default(false);             // 3.2.3 (1%)

            //    3. Vigilancia condiciones de salud (6%) → seis sub-ítems de 1% cada uno
            $table->boolean('hacer_salud_frecuencia_accidentalidad')->default(false);   // 3.3.1 (1%)
            $table->boolean('hacer_salud_severidad_accidentalidad')->default(false);   // 3.3.2 (1%)
            $table->boolean('hacer_salud_mortalidad_accidentes')->default(false);       // 3.3.3 (1%)
            $table->boolean('hacer_salud_prevalencia_enfermedad')->default(false);     // 3.3.4 (1%)
            $table->boolean('hacer_salud_incidencia_enfermedad')->default(false);      // 3.3.5 (1%)
            $table->boolean('hacer_salud_ausentismo_causa_medica')->default(false);     // 3.3.6 (1%)

            //
            // === HACER → GESTIÓN DE PELIGROS Y RIESGOS (30%)
            //    1. Identificación (15%) → cuatro sub‐ítems con pesos 4%, 4%, 3%, 4%
            //
            $table->boolean('hacer_riesgos_metodologia_identificacion')->default(false);              // 4.1.1 (4%)
            $table->boolean('hacer_riesgos_identificacion_participacion')->default(false);             // 4.1.2 (4%)
            $table->boolean('hacer_riesgos_identificacion_sustancias_cancerigenas')->default(false);  // 4.1.3 (3%)
            $table->boolean('hacer_riesgos_mediciones_ambientales')->default(false);                  // 4.1.4 (4%)

            //    2. Medidas de prevención/control (15%) → seis sub-ítems de 2.5% cada uno
            $table->boolean('hacer_riesgos_medidas_prevencion_control')->default(false);            // 4.2.1 (2.5%)
            $table->boolean('hacer_riesgos_verificacion_trabajadores')->default(false);             // 4.2.2 (2.5%)
            $table->boolean('hacer_riesgos_elaboracion_procedimientos')->default(false);            // 4.2.3 (2.5%)
            $table->boolean('hacer_riesgos_inspecciones_copasst')->default(false);                  // 4.2.4 (2.5%)
            $table->boolean('hacer_riesgos_mantenimiento_periodico')->default(false);               // 4.2.5 (2.5%)
            $table->boolean('hacer_riesgos_entrega_epp')->default(false);                           // 4.2.6 (2.5%)

            //
            // === HACER → GESTIÓN DE AMENAZAS (10%)
            //    Plan de emergencia (10%) → dos sub-ítems de 5% cada uno
            //
            $table->boolean('hacer_amenazas_plan_emergencia')->default(false);     // 5.1.1 (5%)
            $table->boolean('hacer_amenazas_brigada_emergencias')->default(false); // 5.1.2 (5%)

            //
            // === VERIFICAR (5%)
            //    Gestión y resultados (5%) → cuatro sub-ítems de 1.25% cada uno
            //
            $table->boolean('verificar_indicadores_sst')->default(false);      // 6.1.1 (1.25%)
            $table->boolean('verificar_auditoria_anual')->default(false);      // 6.1.2 (1.25%)
            $table->boolean('verificar_revision_alta_direccion')->default(false); // 6.1.3 (1.25%)
            $table->boolean('verificar_planificacion_copasst')->default(false);  // 6.1.4 (1.25%)

            //
            // === ACTUAR (10%)
            //    Mejoramiento (10%) → cuatro sub-ítems de 2.5% cada uno
            //
            $table->boolean('actuar_acciones_prev_y_corr')->default(false);          // 7.1.1 (2.5%)
            $table->boolean('actuar_mejoras_revision_alta_direccion')->default(false); // 7.1.2 (2.5%)
            $table->boolean('actuar_mejoras_investigacion')->default(false);         // 7.1.3 (2.5%)
            $table->boolean('actuar_plan_mejoramiento')->default(false);             // 7.1.4 (2.5%)

            //
            // PUNTAJE TOTAL (calculado posteriormente)
            //
            $table->decimal('score_total', 5, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluacion_estandares_minimos');
    }
}
