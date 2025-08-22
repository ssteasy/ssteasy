<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Empresa;
use Illuminate\Support\Carbon;

class EmpleadosStats extends StatsOverviewWidget
{
    public Empresa $empresa;

    protected function getStats(): array
    {
        $activos = $this->empresa
            ->users()
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                  ->orWhere('fecha_fin', '>=', Carbon::today());
            })
            ->count();

        return [
            Stat::make('Empleados activos', $activos)
                ->description('Total actualmente en la empresa')
                ->icon('heroicon-o-user-group'),
        ];
    }
}
