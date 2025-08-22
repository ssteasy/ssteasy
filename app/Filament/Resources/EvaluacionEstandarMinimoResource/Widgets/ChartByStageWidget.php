<?php

namespace App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets;

use App\Models\EvaluacionEstandarMinimo;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\Auth;

class ChartByStageWidget extends LineChartWidget
{
    protected static ?string $heading = 'Comparativo Fases';

    protected function getData(): array
    {
        $empresaId = Auth::user()->empresa_id;
        $year      = now()->year;

        // 1) Intentamos cargar la evaluación de esta empresa y año
        $record = EvaluacionEstandarMinimo::where('empresa_id', $empresaId)
            ->where('year', $year)
            ->first();

        // 2) Definimos las 4 fases con sus campos y pesos
        $phases = [
            'Planear (25%)'   => [
                'planear_recursos_responsable_sst'           => 0.5,
                'planear_recursos_responsabilidades_sst'     => 0.5,
                'planear_recursos_asignacion_recursos'       => 0.5,
                'planear_recursos_afiliacion_sg_riesgos'     => 0.5,
                'planear_recursos_pension_altoriesgo'        => 0.5,
                'planear_recursos_conformacion_copasst'      => 0.5,
                'planear_recursos_capacitacion_copasst'      => 0.5,
                'planear_recursos_conformacion_convivencia'  => 0.5,
                'planear_capacitacion_programa_pyP'          => 2.0,
                'planear_capacitacion_induccion_reinduccion' => 2.0,
                'planear_capacitacion_responsable_curso50h'  => 2.0,
                'planear_gestion_integral_politica_sst'           => 1.0,
                'planear_gestion_integral_objetivos_sst'          => 1.0,
                'planear_gestion_integral_evaluacion_prioridades' => 1.0,
                'planear_gestion_integral_plan_objetivos'         => 2.0,
                'planear_gestion_integral_retencion_documental'    => 2.0,
                'planear_gestion_integral_rendicion_desempeno'     => 1.0,
                'planear_gestion_integral_matriz_legal'            => 2.0,
                'planear_gestion_integral_comunicacion_sst'        => 1.0,
                'planear_gestion_integral_adquisicion_productos'   => 1.0,
                'planear_gestion_integral_evaluacion_proveedores'  => 2.0,
                'planear_gestion_integral_impacto_cambios'         => 1.0,
            ],
            'Hacer (60%)'     => [
                'hacer_salud_evaluacion_medica_ocupacional'    => 1.0,
                'hacer_salud_actividades_promocion_prevencion' => 1.0,
                'hacer_salud_perfiles_cargo_info_medico'       => 1.0,
                'hacer_salud_examenes_ocupacionales'           => 1.0,
                'hacer_salud_custodia_historias_clinicas'      => 1.0,
                'hacer_salud_restricciones_recomendaciones'    => 1.0,
                'hacer_salud_estilos_vida_entornos_saludables' => 1.0,
                'hacer_salud_agua_potable_sanitarios'          => 1.0,
                'hacer_salud_eliminacion_residuos'             => 1.0,
                'hacer_salud_reporte_accidentes_enfermedades'  => 2.0,
                'hacer_salud_investigacion_accidentes'         => 2.0,
                'hacer_salud_registro_estadistico'             => 1.0,
                'hacer_salud_frecuencia_accidentalidad'        => 1.0,
                'hacer_salud_severidad_accidentalidad'         => 1.0,
                'hacer_salud_mortalidad_accidentes'            => 1.0,
                'hacer_salud_prevalencia_enfermedad'           => 1.0,
                'hacer_salud_incidencia_enfermedad'            => 1.0,
                'hacer_salud_ausentismo_causa_medica'          => 1.0,
                'hacer_riesgos_metodologia_identificacion'     => 4.0,
                'hacer_riesgos_identificacion_participacion'   => 4.0,
                'hacer_riesgos_identificacion_sustancias_cancerigenas' => 3.0,
                'hacer_riesgos_mediciones_ambientales'         => 4.0,
                'hacer_riesgos_medidas_prevencion_control'     => 2.5,
                'hacer_riesgos_verificacion_trabajadores'      => 2.5,
                'hacer_riesgos_elaboracion_procedimientos'     => 2.5,
                'hacer_riesgos_inspecciones_copasst'           => 2.5,
                'hacer_riesgos_mantenimiento_periodico'        => 2.5,
                'hacer_riesgos_entrega_epp'                    => 2.5,
                'hacer_amenazas_plan_emergencia'               => 5.0,
                'hacer_amenazas_brigada_emergencias'           => 5.0,
            ],
            'Verificar (5%)'  => [
                'verificar_indicadores_sst'         => 1.25,
                'verificar_auditoria_anual'         => 1.25,
                'verificar_revision_alta_direccion' => 1.25,
                'verificar_planificacion_copasst'   => 1.25,
            ],
            'Actuar (10%)'    => [
                'actuar_acciones_prev_y_corr'            => 2.5,
                'actuar_mejoras_revision_alta_direccion' => 2.5,
                'actuar_mejoras_investigacion'           => 2.5,
                'actuar_plan_mejoramiento'               => 2.5,
            ],
        ];

        $labels   = array_keys($phases);
        $maxima   = array_map(fn($fields) => round(array_sum($fields), 2), $phases);
        $actual   = [];

        // 3) Si no hay registro, actual = zeros
        if (! $record) {
            $actual = array_fill(0, count($labels), 0);
        } else {
            foreach ($phases as $fields) {
                $sumActual = 0;
                foreach ($fields as $field => $weight) {
                    if (in_array($record->{$field}, ['cumple', 'no_aplica'], true)) {
                        $sumActual += $weight;
                    }
                }
                $actual[] = round($sumActual, 2);
            }
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label' => 'Actual (%)',
                    'data'  => $actual,
                ],
                [
                    'label' => 'Máximo (%)',
                    'data'  => $maxima,
                ],
            ],
        ];
    }
}
