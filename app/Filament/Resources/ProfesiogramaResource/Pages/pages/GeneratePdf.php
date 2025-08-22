<?php
namespace App\Filament\Resources\ProfesiogramaResource\Pages;

use Dompdf\Dompdf;
use Filament\Pages\Page;
use App\Models\Profesiograma;

class GeneratePdf extends Page
{
    public static function run(Profesiograma $record)
    {
        $pdf = new Dompdf();
        $html = view('profesiogramas.pdf', compact('record'))->render();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4','portrait');
        $pdf->render();
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "profesiograma-{$record->cargo->nombre}.pdf"
        );
    }
}
