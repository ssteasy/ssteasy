<?php

namespace App\Filament\Resources\PlanTrabajoAnualResource\Widgets;

use App\Models\PlanTrabajoAnual;
use Filament\Widgets\Widget;

class PlanTrabajoCalendarWidget extends Widget
{
    /** Blade view  */
    protected static string $view = 'filament.resources.plan-trabajo-anual.widgets.calendar';

    /** Ancho: una sola columna (o 'full') */
    protected int|string|array $columnSpan = 'full';

    /* ------------------------------------------------------------------ */
    public int   $year;
    public int   $empresaId;
    public array $monthData = [];   // datos precargados

    public function mount(): void
    {
        $this->year      = now()->year;
        $this->empresaId = auth()->user()->empresa_id;

        $plan = PlanTrabajoAnual::with('actividades')
            ->where('empresa_id', $this->empresaId)
            ->where('year',       $this->year)
            ->first();

        $meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];

        foreach (range(1, 12) as $i) {
            $campo = $meses[$i - 1];

            $acts = $plan
                ? $plan->actividades->filter(fn ($a) => $a->{"mes_$campo"} !== 'no_aplica')
                : collect();

            $kpi = ['planear'=>0,'pospuesta'=>0,'ejecutada'=>0];
            foreach ($acts as $a) {
                $estado = $a->{"mes_$campo"};
                if (isset($kpi[$estado])) {
                    $kpi[$estado]++;
                }
            }

            $this->monthData[$i] = [
                'actividades' => $acts,
                'kpis'        => $kpi,
            ];
        }
    }
}
