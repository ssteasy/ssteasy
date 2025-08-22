<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionEstandarMinimo extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_estandares_minimos';

    protected $fillable = [
        'empresa_id',
        'year',

        // ==== “PLANEAR” (10%) – booleanos:
        'planear_recursos_responsable_sst',
        'planear_recursos_responsabilidades_sst',
        'planear_recursos_asignacion_recursos',
        'planear_recursos_afiliacion_sg_riesgos',
        'planear_recursos_pension_altoriesgo',
        'planear_recursos_conformacion_copasst',
        'planear_recursos_capacitacion_copasst',
        'planear_recursos_conformacion_convivencia',
        'planear_capacitacion_programa_pyP',
        'planear_capacitacion_induccion_reinduccion',
        'planear_capacitacion_responsable_curso50h',

        // ==== “PLANEAR” – nuevos campos de adjunto (string)
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

        // ==== NUEVOS: Gestión Integral SG-SST (15%)
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

        // ==== archivos adjuntos
        'planear_gestion_integral_politica_sst_archivo',
        'planear_gestion_integral_objetivos_sst_archivo',
        'planear_gestion_integral_evaluacion_prioridades_archivo',
        'planear_gestion_integral_plan_objetivos_archivo',
        'planear_gestion_integral_retencion_documental_archivo',
        'planear_gestion_integral_rendicion_desempeno_archivo',
        'planear_gestion_integral_matriz_legal_archivo',
        'planear_gestion_integral_comunicacion_sst_archivo',
        'planear_gestion_integral_adquisicion_productos_archivo',
        'planear_gestion_integral_evaluacion_proveedores_archivo',
        'planear_gestion_integral_impacto_cambios_archivo',

        // ==== “HACER” → Gestión de la Salud (20%) – booleanos
        'hacer_salud_evaluacion_medica_ocupacional',
        'hacer_salud_actividades_promocion_prevencion',
        'hacer_salud_perfiles_cargo_info_medico',
        'hacer_salud_examenes_ocupacionales',
        'hacer_salud_custodia_historias_clinicas',
        'hacer_salud_restricciones_recomendaciones',
        'hacer_salud_estilos_vida_entornos_saludables',
        'hacer_salud_agua_potable_sanitarios',
        'hacer_salud_eliminacion_residuos',
        'hacer_salud_reporte_accidentes_enfermedades',
        'hacer_salud_investigacion_accidentes',
        'hacer_salud_registro_estadistico',
        'hacer_salud_frecuencia_accidentalidad',
        'hacer_salud_severidad_accidentalidad',
        'hacer_salud_mortalidad_accidentes',
        'hacer_salud_prevalencia_enfermedad',
        'hacer_salud_incidencia_enfermedad',
        'hacer_salud_ausentismo_causa_medica',

        // ==== “HACER” → Gestión de la Salud – nuevos campos adjunto
        'hacer_salud_evaluacion_medica_ocupacional_archivo',
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

        // ==== “HACER” → Gestión de Peligros y Riesgos (30%) – booleanos
        'hacer_riesgos_metodologia_identificacion',
        'hacer_riesgos_identificacion_participacion',
        'hacer_riesgos_identificacion_sustancias_cancerigenas',
        'hacer_riesgos_mediciones_ambientales',
        'hacer_riesgos_medidas_prevencion_control',
        'hacer_riesgos_verificacion_trabajadores',
        'hacer_riesgos_elaboracion_procedimientos',
        'hacer_riesgos_inspecciones_copasst',
        'hacer_riesgos_mantenimiento_periodico',
        'hacer_riesgos_entrega_epp',

        // ==== “HACER” → Gestión de Peligros y Riesgos – nuevos adjuntos
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

        // ==== “HACER” → Gestión de Amenazas (10%) – booleanos
        'hacer_amenazas_plan_emergencia',
        'hacer_amenazas_brigada_emergencias',

        // ==== “HACER” → Gestión de Amenazas – adjuntos
        'hacer_amenazas_plan_emergencia_archivo',
        'hacer_amenazas_brigada_emergencias_archivo',

        // ==== “VERIFICAR” (5%) – booleanos
        'verificar_indicadores_sst',
        'verificar_auditoria_anual',
        'verificar_revision_alta_direccion',
        'verificar_planificacion_copasst',

        // ==== “VERIFICAR” – adjuntos
        'verificar_indicadores_sst_archivo',
        'verificar_auditoria_anual_archivo',
        'verificar_revision_alta_direccion_archivo',
        'verificar_planificacion_copasst_archivo',

        // ==== “ACTUAR” (10%) – booleanos
        'actuar_acciones_prev_y_corr',
        'actuar_mejoras_revision_alta_direccion',
        'actuar_mejoras_investigacion',
        'actuar_plan_mejoramiento',

        // ==== “ACTUAR” – adjuntos
        'actuar_acciones_prev_y_corr_archivo',
        'actuar_mejoras_revision_alta_direccion_archivo',
        'actuar_mejoras_investigacion_archivo',
        'actuar_plan_mejoramiento_archivo',

        // ==== Puntaje calculado
        'score_total',
    ];

    /**
     * Relación con Empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Cálculo automático de score_total
     */
    public static function booted()
    {
        static::saving(function ($model) {
            $total = 0.0;
            $sumaPeso = fn($v) => in_array($v, ['cumple','no_aplica']);

            // === PLANEAR (10%)
            $pesosRecursos = [
                'planear_recursos_responsable_sst'         => 0.5,
                'planear_recursos_responsabilidades_sst'   => 0.5,
                'planear_recursos_asignacion_recursos'     => 0.5,
                'planear_recursos_afiliacion_sg_riesgos'   => 0.5,
                'planear_recursos_pension_altoriesgo'      => 0.5,
                'planear_recursos_conformacion_copasst'    => 0.5,
                'planear_recursos_capacitacion_copasst'    => 0.5,
                'planear_recursos_conformacion_convivencia'=> 0.5,
            ];
            foreach ($pesosRecursos as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            $pesosCapPlanear = [
                'planear_capacitacion_programa_pyP'           => 2.0,
                'planear_capacitacion_induccion_reinduccion'  => 2.0,
                'planear_capacitacion_responsable_curso50h'    => 2.0,
            ];
            foreach ($pesosCapPlanear as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

             // ==== NUEVO bloque “Gestión Integral” (15%)
             $pesosIntegral = [
                'planear_gestion_integral_politica_sst'                 => 1.0,
                'planear_gestion_integral_objetivos_sst'                => 1.0,
                'planear_gestion_integral_evaluacion_prioridades'       => 1.0,
                'planear_gestion_integral_plan_objetivos'               => 2.0,
                'planear_gestion_integral_retencion_documental'          => 2.0,
                'planear_gestion_integral_rendicion_desempeno'           => 1.0,
                'planear_gestion_integral_matriz_legal'                  => 2.0,
                'planear_gestion_integral_comunicacion_sst'             => 1.0,
                'planear_gestion_integral_adquisicion_productos'         => 1.0,
                'planear_gestion_integral_evaluacion_proveedores'        => 2.0,
                'planear_gestion_integral_impacto_cambios'               => 1.0,
            ];
            foreach ($pesosIntegral as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            // === HACER → Gestión de la Salud (20%)
            $pesosSalud1 = [
                'hacer_salud_evaluacion_medica_ocupacional'      => 1.0,
                'hacer_salud_actividades_promocion_prevencion'  => 1.0,
                'hacer_salud_perfiles_cargo_info_medico'        => 1.0,
                'hacer_salud_examenes_ocupacionales'            => 1.0,
                'hacer_salud_custodia_historias_clinicas'       => 1.0,
                'hacer_salud_restricciones_recomendaciones'     => 1.0,
                'hacer_salud_estilos_vida_entornos_saludables'  => 1.0,
                'hacer_salud_agua_potable_sanitarios'           => 1.0,
                'hacer_salud_eliminacion_residuos'              => 1.0,
            ];
            foreach ($pesosSalud1 as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            $pesosSalud2 = [
                'hacer_salud_reporte_accidentes_enfermedades' => 2.0,
                'hacer_salud_investigacion_accidentes'        => 2.0,
                'hacer_salud_registro_estadistico'            => 1.0,
            ];
            foreach ($pesosSalud2 as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            $pesosSalud3 = [
                'hacer_salud_frecuencia_accidentalidad' => 1.0,
                'hacer_salud_severidad_accidentalidad'  => 1.0,
                'hacer_salud_mortalidad_accidentes'     => 1.0,
                'hacer_salud_prevalencia_enfermedad'    => 1.0,
                'hacer_salud_incidencia_enfermedad'     => 1.0,
                'hacer_salud_ausentismo_causa_medica'   => 1.0,
            ];
            foreach ($pesosSalud3 as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            // === HACER → Gestión de Peligros y Riesgos (30%)
            $pesosRiesgos1 = [
                'hacer_riesgos_metodologia_identificacion'             => 4.0,
                'hacer_riesgos_identificacion_participacion'           => 4.0,
                'hacer_riesgos_identificacion_sustancias_cancerigenas'=> 3.0,
                'hacer_riesgos_mediciones_ambientales'                 => 4.0,
            ];
            foreach ($pesosRiesgos1 as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            $pesosRiesgos2 = [
                'hacer_riesgos_medidas_prevencion_control' => 2.5,
                'hacer_riesgos_verificacion_trabajadores'  => 2.5,
                'hacer_riesgos_elaboracion_procedimientos' => 2.5,
                'hacer_riesgos_inspecciones_copasst'       => 2.5,
                'hacer_riesgos_mantenimiento_periodico'    => 2.5,
                'hacer_riesgos_entrega_epp'                => 2.5,
            ];
            foreach ($pesosRiesgos2 as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            // === HACER → Gestión de Amenazas (10%)
            $pesosAmenazas = [
                'hacer_amenazas_plan_emergencia'    => 5.0,
                'hacer_amenazas_brigada_emergencias'=> 5.0,
            ];
            foreach ($pesosAmenazas as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            // === VERIFICAR (5%)
            $pesosVerificar = [
                'verificar_indicadores_sst'         => 1.25,
                'verificar_auditoria_anual'         => 1.25,
                'verificar_revision_alta_direccion' => 1.25,
                'verificar_planificacion_copasst'   => 1.25,
            ];
            foreach ($pesosVerificar as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            // === ACTUAR (10%)
            $pesosActuar = [
                'actuar_acciones_prev_y_corr'            => 2.5,
                'actuar_mejoras_revision_alta_direccion' => 2.5,
                'actuar_mejoras_investigacion'           => 2.5,
                'actuar_plan_mejoramiento'               => 2.5,
            ];
            foreach ($pesosActuar as $campo => $peso) {
                if ($sumaPeso($model->{$campo})) {
                    $total += $peso;
                }
            }

            $model->score_total = round($total, 2);
        });
    }
}
