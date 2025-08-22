<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Empresa;
use Illuminate\Support\Carbon;
use App\Filament\Widgets\EmpleadosStats;   // ← importa el widget

class MiEmpresa extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Mi empresa';
    protected static ?string $slug            = 'miempresa';
    protected static ?string $title           = 'Mi empresa';
    protected static ?int    $navigationSort  = 0;

    protected static string $view = 'filament.pages.mi-empresa';

    public Empresa $empresa;
    public int     $empleadosCount;

    /** ─────────────────────────
     *  CICLO DE VIDA
     * ───────────────────────── */
    public function mount(): void
    {
        $this->empresa = auth()->user()->empresa;

        // Conteo de empleados activos (fecha_fin nula o futura)
        $this->empleadosCount = $this->empresa
            ->users()
            ->where(fn ($q) => $q
                ->whereNull('fecha_fin')
                ->orWhere('fecha_fin', '>=', Carbon::today()))
            ->count();
    }

    /** ─────────────────────────
     *  WIDGETS DE CABECERA
     * ───────────────────────── */
    protected function getHeaderWidgets(): array
    {
        // Pasamos la empresa como parámetro al widget
        return [
            EmpleadosStats::class => [
                'empresa' => $this->empresa,
            ],
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view', Empresa::class) ?? false;
    }
}
