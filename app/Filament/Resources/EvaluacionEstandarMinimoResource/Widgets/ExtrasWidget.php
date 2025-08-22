<?php

namespace App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets;

use App\Models\EvaluacionEstandarMinimo;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card as StatsCard;
use Illuminate\Support\Facades\Auth;

class ExtrasWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Datos Adicionales';

    public function getCards(): array
    {
        $empresaId   = Auth::user()->empresa_id;
        $currentYear = now()->year;
        $lastYear    = $currentYear - 1;

        // 1) Cargamos los registros
        $currentRecord = EvaluacionEstandarMinimo::where([
            ['empresa_id', $empresaId],
            ['year', $currentYear],
        ])->first();

        $lastRecord = EvaluacionEstandarMinimo::where([
            ['empresa_id', $empresaId],
            ['year', $lastYear],
        ])->first();

        $all = EvaluacionEstandarMinimo::where('empresa_id', $empresaId);

        // 2) Calculamos valores con fallback
        $currentScore = $currentRecord?->score_total ?? 0;
        $lastScore    = $lastRecord?->score_total ?? 0;
        $diffScore    = round($currentScore - $lastScore, 2);

        // 3) Mejor y peor histórico (pueden no existir)
        $best  = $all->orderByDesc('score_total')->first();
        $worst = $all->orderBy('score_total')->first();

        $bestYear  = optional($best)->year  ?:"N/A";
        $bestScore = optional($best)->score_total !== null
                     ? "{$best->score_total}%" 
                     : "N/A";

        $worstYear  = optional($worst)->year  ?:"N/A";
        $worstScore = optional($worst)->score_total !== null
                      ? "{$worst->score_total}%"
                      : "N/A";

        $lastUpdated = optional($currentRecord)->updated_at
                       ?->format('d/m/Y')
                       ?? 'N/A';

        // 4) Estado actual
        if ($currentScore < 60) {
            $state      = 'Crítico';
            $stateColor = 'danger';
        } elseif ($currentScore <= 85) {
            $state      = 'Moderado';
            $stateColor = 'warning';
        } else {
            $state      = 'Aceptable';
            $stateColor = 'success';
        }

        // 5) Armamos las tarjetas
        return [
            StatsCard::make("Estado {$currentYear}", $state)
                ->description("{$currentScore}%")
                ->color($stateColor),

            // comparación con año anterior (solo si existe lastRecord)
            StatsCard::make("Promedio {$lastYear} (%)", "{$lastScore}%")
                ->description($lastRecord ? round($lastScore,2)."%": "N/A")
                ->descriptionColor($diffScore >= 0 ? 'success' : 'danger')
                ->description(($diffScore >= 0 ? '+' : '')."{$diffScore}%"),

            StatsCard::make("Mejor Puntaje {$bestYear}", $bestScore),
            StatsCard::make("Peor Puntaje {$worstYear}", $worstScore),

            StatsCard::make('Última Actualización', $lastUpdated),
        ];
    }
}
