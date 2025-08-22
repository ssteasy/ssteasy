<?php

namespace App\Exports;

use App\Models\EvaluacionEstandarMinimo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Concerns\{
    FromArray,
    ShouldAutoSize,
    WithEvents,
    WithDrawings,
    Exportable
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EvaluacionEstandarMinimoExport implements FromArray, ShouldAutoSize, WithEvents, WithDrawings
{
    use Exportable;

    protected EvaluacionEstandarMinimo $record;

    public function __construct(EvaluacionEstandarMinimo $record)
    {
        $this->record = $record;
    }

    public function array(): array
    {
        $r = $this->record;
        $e = $r->empresa;
        $rows = [];

        // 1) TÍTULO
        $rows[] = ["REPORTE SG-SST: {$e->nombre} — Año {$r->year}", '', ''];
        $rows[] = [];

        // 2) DATOS DE LA EMPRESA
        $rows[] = ['DATOS DE LA EMPRESA', '', ''];
        $rows[] = ['Empresa', $e->nombre, ''];
        $rows[] = ['NIT', $e->nit, ''];
        $rows[] = ['Razón Social', $e->razon_social, ''];
        $rows[] = ['Email', $e->email, ''];
        $rows[] = ['Teléfono', $e->telefono, ''];
        $rows[] = ['Dirección', $e->direccion, ''];
        $rows[] = ['Ciudad', $e->ciudad, ''];
        $rows[] = ['Website', $e->website, ''];
        $rows[] = [];

        // 3) METADATOS DE LA EVALUACIÓN
        $rows[] = ['METADATOS DE LA EVALUACIÓN', '', ''];
        $rows[] = ['Año', $r->year, ''];
        $rows[] = ['Puntaje Total (%)', $r->score_total, ''];
        $rows[] = [];

        // 4) SECCIONES PRINCIPALES y SUBSECCIONES
        $structure = [

            // PLANEAR
            'PLANEAR (25%)' => [
                'Recursos (4%)' => [
                    'planear_recursos_responsable_sst' => '1.1.1 Responsable SG-SST (0.5%)',
                    'planear_recursos_responsabilidades_sst' => '1.1.2 Responsabilidades SG-SST (0.5%)',
                    'planear_recursos_asignacion_recursos' => '1.1.3 Asignación de recursos (0.5%)',
                    'planear_recursos_afiliacion_sg_riesgos' => '1.1.4 Afiliación SG Riesgos Laborales (0.5%)',
                    'planear_recursos_pension_altoriesgo' => '1.1.5 Pago de pensión alto riesgo (0.5%)',
                    'planear_recursos_conformacion_copasst' => '1.1.6 Conformación COPASST (0.5%)',
                    'planear_recursos_capacitacion_copasst' => '1.1.7 Capacitación COPASST (0.5%)',
                    'planear_recursos_conformacion_convivencia' => '1.1.8 Comité Convivencia (0.5%)',
                ],
                'Capacitación (6%)' => [
                    'planear_capacitacion_programa_pyP' => '1.2.1 Programa PyP (2%)',
                    'planear_capacitacion_induccion_reinduccion' => '1.2.2 Inducción / Reinducción (2%)',
                    'planear_capacitacion_responsable_curso50h' => '1.2.3 Responsable con curso 50h (2%)',
                ],
                'Gestión Integral (15%)' => [
                    'planear_gestion_integral_politica_sst' => '2.1.1 Política SG-SST (1%)',
                    'planear_gestion_integral_objetivos_sst' => '2.2.1 Objetivos SG-SST (1%)',
                    'planear_gestion_integral_evaluacion_prioridades' => '2.3.1 Evaluación e identificación prioridades (1%)',
                    'planear_gestion_integral_plan_objetivos' => '2.4.1 Plan objetivos y cronograma (2%)',
                    'planear_gestion_integral_retencion_documental' => '2.5.1 Retención documental (2%)',
                    'planear_gestion_integral_rendicion_desempeno' => '2.6.1 Rendición desempeño (1%)',
                    'planear_gestion_integral_matriz_legal' => '2.7.1 Matriz legal (2%)',
                    'planear_gestion_integral_comunicacion_sst' => '2.8.1 Comunicación SG-SST (1%)',
                    'planear_gestion_integral_adquisicion_productos' => '2.9.1 Adquisición productos/servicios (1%)',
                    'planear_gestion_integral_evaluacion_proveedores' => '2.10.1 Selección de proveedores (2%)',
                    'planear_gestion_integral_impacto_cambios' => '2.11.1 Impacto de cambios (1%)',
                ],
            ],

            // HACER
            'HACER (60%)' => [
                'Gestión de la Salud (20%)' => [
                    'hacer_salud_evaluacion_medica_ocupacional' => '3.1.1 Evaluación Médica Ocupacional (1%)',
                    'hacer_salud_actividades_promocion_prevencion' => '3.1.2 Promoción y Prevención (1%)',
                    'hacer_salud_perfiles_cargo_info_medico' => '3.1.3 Perfiles e info. médica (1%)',
                    'hacer_salud_examenes_ocupacionales' => '3.1.4 Exámenes Ocupacionales (1%)',
                    'hacer_salud_custodia_historias_clinicas' => '3.1.5 Custodia Historias Clínicas (1%)',
                    'hacer_salud_restricciones_recomendaciones' => '3.1.6 Restricciones/Recomendaciones (1%)',
                    'hacer_salud_estilos_vida_entornos_saludables' => '3.1.7 Entornos saludables (1%)',
                    'hacer_salud_agua_potable_sanitarios' => '3.1.8 Agua potable y sanitarios (1%)',
                    'hacer_salud_eliminacion_residuos' => '3.1.9 Eliminación de residuos (1%)',
                    'hacer_salud_reporte_accidentes_enfermedades' => '3.2.1 Reporte Accidentes (2%)',
                    'hacer_salud_investigacion_accidentes' => '3.2.2 Investigación Accidentes (2%)',
                    'hacer_salud_registro_estadistico' => '3.2.3 Registro Estadístico (1%)',
                    'hacer_salud_frecuencia_accidentalidad' => '3.3.1 Frecuencia (1%)',
                    'hacer_salud_severidad_accidentalidad' => '3.3.2 Severidad (1%)',
                    'hacer_salud_mortalidad_accidentes' => '3.3.3 Mortalidad (1%)',
                    'hacer_salud_prevalencia_enfermedad' => '3.3.4 Prevalencia (1%)',
                    'hacer_salud_incidencia_enfermedad' => '3.3.5 Incidencia (1%)',
                    'hacer_salud_ausentismo_causa_medica' => '3.3.6 Ausentismo (1%)',
                ],
                'Peligros y Riesgos (30%)' => [
                    'hacer_riesgos_metodologia_identificacion' => '4.1.1 Metodología (4%)',
                    'hacer_riesgos_identificacion_participacion' => '4.1.2 Participativa (4%)',
                    'hacer_riesgos_identificacion_sustancias_cancerigenas' => '4.1.3 Cancerígenas (3%)',
                    'hacer_riesgos_mediciones_ambientales' => '4.1.4 Mediciones Ambientales (4%)',
                    'hacer_riesgos_medidas_prevencion_control' => '4.2.1 Prevención/Control (2.5%)',
                    'hacer_riesgos_verificacion_trabajadores' => '4.2.2 Verificación (2.5%)',
                    'hacer_riesgos_elaboracion_procedimientos' => '4.2.3 Procedimientos (2.5%)',
                    'hacer_riesgos_inspecciones_copasst' => '4.2.4 Inspecciones COPASST (2.5%)',
                    'hacer_riesgos_mantenimiento_periodico' => '4.2.5 Mantenimiento (2.5%)',
                    'hacer_riesgos_entrega_epp' => '4.2.6 Entrega de EPP (2.5%)',
                ],
                'Amenazas (10%)' => [
                    'hacer_amenazas_plan_emergencia' => '5.1.1 Plan Emergencia (5%)',
                    'hacer_amenazas_brigada_emergencias' => '5.1.2 Brigada Emergencias (5%)',
                ],
            ],

            // VERIFICAR
            'VERIFICAR (5%)' => [
                'verificar_indicadores_sst' => '6.1.1 Indicadores (1.25%)',
                'verificar_auditoria_anual' => '6.1.2 Auditoría Anual (1.25%)',
                'verificar_revision_alta_direccion' => '6.1.3 Revisión Alta Dirección (1.25%)',
                'verificar_planificacion_copasst' => '6.1.4 Planificación COPASST (1.25%)',
            ],

            // ACTUAR
            'ACTUAR (10%)' => [
                'actuar_acciones_prev_y_corr' => '7.1.1 Preventivas/Correctivas (2.5%)',
                'actuar_mejoras_revision_alta_direccion' => '7.1.2 Mejoras tras Revisión (2.5%)',
                'actuar_mejoras_investigacion' => '7.1.3 Mejoras tras Investigación (2.5%)',
                'actuar_plan_mejoramiento' => '7.1.4 Plan de Mejoramiento (2.5%)',
            ],
        ];

        // Recorrer la estructura
        foreach ($structure as $section => $sectionContent) {
            // sección principal
            $rows[] = [$section, '', ''];

            // vemos el primer valor de sectionContent
            $first = reset($sectionContent);
            if (is_array($first)) {
                // hay subsecciones
                foreach ($sectionContent as $subsec => $fields) {
                    $rows[] = [$subsec, '', ''];
                    foreach ($fields as $campo => $etiqueta) {
                        $valor = $r->{$campo};
                        $archivo = $r->{"{$campo}_archivo"};
                        $url = $archivo ? URL::to(Storage::url($archivo)) : '';
                        $rows[] = [$etiqueta, ucfirst(str_replace('_', ' ', $valor)), $url];
                    }
                    $rows[] = []; // espacio tras cada subsección
                }
            } else {
                // es un mapeo directo campo=>etiqueta (p.ej. Verificar, Actuar)
                foreach ($sectionContent as $campo => $etiqueta) {
                    $valor = $r->{$campo};
                    $archivo = $r->{"{$campo}_archivo"};
                    $url = $archivo ? URL::to(Storage::url($archivo)) : '';
                    $rows[] = [$etiqueta, ucfirst(str_replace('_', ' ', $valor)), $url];
                }
                $rows[] = []; // espacio tras la sección plana
            }

            $rows[] = []; // espacio entre secciones principales
        }

        return $rows;
    }

    public function drawings()
    {
        $drawings = [];
        $e = $this->record->empresa;


        $appLogo = new Drawing();
        $appLogo->setName('ssteasy Logo');
        $appLogo->setDescription('Logo de ssteasy');
        $appLogo->setPath('https://app.ssteasy.com/images/lhlight_logo.png');
        $appLogo->setHeight(80);
        $appLogo->setCoordinates('B1');
        $drawings[] = $appLogo;


        $path = storage_path('app/public/' . $e->logo);
        if ($e->logo && file_exists($path)) {
            $companyLogo = new Drawing();
            $companyLogo->setName('Logo Empresa');
            $companyLogo->setPath($path);
            $companyLogo->setHeight(80);
            $companyLogo->setCoordinates('C1');
            $drawings[] = $companyLogo;
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        $mainBgColor = '11456d';
        $subBgColor = '175880';

        return [
            AfterSheet::class => function (AfterSheet $event) use ($mainBgColor, $subBgColor) {
                // Obtenemos el Worksheet de PhpSpreadsheet
                $sheet = $event->sheet->getDelegate();
                // Fila 1 más alta
                // Fila 1 más alta y texto grande
                $sheet->getRowDimension(1)->setRowHeight(50);
                $sheet->getStyle('A1:C1')->getFont()->setSize(18);

                // ── Ajustes para A1:C1 ──
                $sheet->getStyle('A1:C1')
                    // fondo #F4F4F5
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('175880');

                $sheet->getStyle('A1:C1')
                    // texto #175880
                    ->getFont()
                    ->getColor()
                    ->setARGB('ddf3ff');

                $sheet->getStyle('A1:C1')
                    // alineación centrada vertical y horizontal
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $highestRow = $sheet->getHighestRow();

                // ── 1) Aplicar fondo y texto blanco en secciones principales
                for ($row = 1; $row <= $highestRow; $row++) {
                    $value = $sheet->getCell("A{$row}")->getValue();

                    // Secciones principales
                    if (preg_match('/^(DATOS DE|METADATOS|PLANEAR|HACER|VERIFICAR|ACTUAR)/i', $value)) {
                        $style = $sheet->getStyle("A{$row}:C{$row}");
                        $style->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB($mainBgColor);
                        $style->getFont()
                            ->setBold(true)
                            ->getColor()->setARGB('FFFFFFFF');
                    }
                    // Subsecciones
                    elseif (preg_match('/^(Recursos|Capacitación|Gestión Integral|Gestión de la Salud|Peligros|Amenazas)/i', $value)) {
                        $style = $sheet->getStyle("A{$row}:C{$row}");
                        $style->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB($subBgColor);
                        $style->getFont()
                            ->setBold(true)
                            ->getColor()->setARGB('FFFFFFFF');
                    }
                }

                // ── Footer centrado ──
                $sheet->getHeaderFooter()
                    ->setOddFooter('&CGenerado por ssteasy');
            },
        ];
    }

}
