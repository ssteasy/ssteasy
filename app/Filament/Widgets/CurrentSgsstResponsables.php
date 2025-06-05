<?php

namespace App\Filament\Widgets;

use App\Models\SgsstResponsable;
use Filament\Widgets\Widget;

class CurrentSgsstResponsables extends Widget
{
    protected static string $view = 'filament.widgets.current-sgsst-responsables';

    public $responsables = [];

    public function mount(): void
    {
        $query = SgsstResponsable::with('user')
            ->whereNull('fecha_fin');

        if (! auth()->user()->hasRole('superadmin')) {
            $query->whereHas('user', fn($q) =>
                $q->where('empresa_id', auth()->user()->empresa_id)
            );
        }

        $this->responsables = $query->get();
    }
}
