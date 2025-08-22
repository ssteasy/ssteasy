<?php

namespace App\Http\Controllers;

use App\Models\PlanTrabajoAnual;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PlanPdfController extends Controller
{
    /**
     * Convierte recursivamente cualquier string a UTF-8.
     */
    private function utf8ize($mixed)
    {
        if (is_string($mixed)) {
            return mb_convert_encoding($mixed, 'UTF-8', 'UTF-8, ISO-8859-1, ASCII');
        }
        if (is_array($mixed)) {
            return array_map([$this, 'utf8ize'], $mixed);
        }
        if (is_object($mixed)) {
            foreach ($mixed as $k => $v) {
                $mixed->$k = $this->utf8ize($v);
            }
        }
        return $mixed;
    }

    /**
     * Genera y descarga el PDF del plan anual.
     */
    public function show(PlanTrabajoAnual $plan): Response
    {
        // Relaciones
        $empresa      = $plan->empresa;
        $actividades  = $plan->actividades()->orderBy('actividad')->get();

        // Estadística básica
        $totalActiv   = $actividades->count();
        $totalMeses   = $totalActiv * 12;
        $ejecutadas   = $actividades->flatMap(fn ($a) =>
            collect([
                $a->mes_ene, $a->mes_feb, $a->mes_mar, $a->mes_abr, $a->mes_may, $a->mes_jun,
                $a->mes_jul, $a->mes_ago, $a->mes_sep, $a->mes_oct, $a->mes_nov, $a->mes_dic,
            ])
        )->filter('ejecutada')->count();

        $cumplimiento = $totalMeses ? round(($ejecutadas / $totalMeses) * 100, 1) : 0;

        // Datos para la vista (forzados a UTF-8)
        $data = $this->utf8ize([
            'plan'         => $plan,
            'empresa'      => $empresa,
            'actividades'  => $actividades,
            'cumplimiento' => $cumplimiento,
        ]);

        // Opciones DomPDF
        $options = [
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'DejaVu Sans',
        ];

        $pdf = Pdf::loadView('pdf.plan-trabajo-anual', $data)
                  ->setPaper('a4', 'portrait')
                  ->setOptions($options)
                  ->setWarnings(false);

        // Nombre de archivo “seguro”
        $slugEmpresa = str()->slug($empresa->nombre);
        $filename    = "plan_{$plan->year}_{$slugEmpresa}.pdf";

        return $pdf->download($filename);
    }
}
