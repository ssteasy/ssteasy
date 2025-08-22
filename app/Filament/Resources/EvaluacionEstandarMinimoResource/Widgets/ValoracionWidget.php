<?php

namespace App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets;

use App\Models\EvaluacionEstandarMinimo;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ValoracionWidget extends StatsOverviewWidget
{
    // Título del widget
    protected ?string $heading = 'Valoración SG-SST';

    protected function getCards(): array
    {
        $record = EvaluacionEstandarMinimo::where('year', now()->year)->first();
        $score  = $record->score_total ?? 0;

        if ($score < 60) {
            $level = 'Crítico';
            $color = 'danger';   // rojo
        } elseif ($score <= 85) {
            $level = 'Moderado';
            $color = 'warning';  // amarillo
        } else {
            $level = 'Aceptable';
            $color = 'success';  // verde
        }

        return [
            Card::make("{$score}%", 'Valoración')
                ->description($level)
                ->descriptionColor($color)
                ->chart($score), // opcional: muestra donut con el % 
        ];
    }
}
