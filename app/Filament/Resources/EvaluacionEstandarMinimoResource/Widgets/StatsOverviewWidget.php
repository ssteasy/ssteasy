<?php

namespace App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets;

use App\Models\EvaluacionEstandarMinimo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Estadísticas Fases (Año Actual)';

    public function getCards(): array
    {
        $year   = now()->year;
        $record = EvaluacionEstandarMinimo::where('year', $year)->first();
        $max    = ['Planear'=>10,'Hacer'=>60,'Verificar'=>5,'Actuar'=>10];
        $score  = fn($fase) => $record?->{"scoreParte"}($fase) ?? 0;

        return collect($max)
            ->map(fn($v,$k) => Card::make("{$k} (Max {$v}%)", "{$score(strtolower($k))}%"))
            ->toArray();
    }
}
