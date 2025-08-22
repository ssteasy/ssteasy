<?php

namespace App\Http\Controllers;

use App\Models\GestionCambio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class GestionCambioPdfController extends Controller
{
    public function download(GestionCambio $cambio)
    {
        $this->authorize('view', $cambio);

        $pdf = Pdf::loadView('pdf.gestion-cambio', compact('cambio'))
                  ->setPaper('letter', 'portrait');

        return $pdf->download("GC-{$cambio->id}.pdf");
    }
}
