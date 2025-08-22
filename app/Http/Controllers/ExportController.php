<?php
// app/Http/Controllers/ExportController.php

namespace App\Http\Controllers;

use App\Models\PlanTrabajoAnual;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class ExportController extends Controller
{
    public function exportPlanPdf(PlanTrabajoAnual $plan)
{
    $empresa = $plan->empresa;

    // Carga la vista de la carpeta pdf/
    $pdf = PDF::loadView('pdf.plan-trabajo-anual', compact('plan','empresa'))
              ->setPaper('a4', 'landscape');

    $filename = "Plan_Trabajo_{$plan->year}_Empresa_{$empresa->id}.pdf";

    return $pdf->download($filename);
}

}
