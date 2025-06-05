<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;

class MisCapacitaciones extends Page
{
    protected static string $view = 'portal.capacitaciones.index';

    public function mount()
    {
        $this->capacitaciones = auth()->user()
            ->capacitaciones()  // vía belongsToMany
            ->with('empresa')
            ->wherePivot('estado','!=','finalizado')
            ->get();
    }
}
