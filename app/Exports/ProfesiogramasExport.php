<?php
// app/Exports/ProfesiogramasExport.php

namespace App\Exports;

use App\Models\Profesiograma;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfesiogramasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Profesiograma::with('cargo')->get()
            ->map(fn($p) => [
                'Cargo'     => $p->cargo->nombre,
                'Tareas'    => $p->tareas,
                'Funciones' => $p->funciones,
                'Riesgos'   => $p->riesgos,
                // etc.
            ]);
    }

    public function headings(): array
    {
        return ['Cargo','Tareas','Funciones','Riesgos', /*…*/];
    }
}
