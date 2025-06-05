<?php

namespace App\Filament\Resources\SgsstResponsableResource\Pages;

use App\Filament\Resources\SgsstResponsableResource;
use Filament\Resources\Pages\Page;
use App\Models\SgsstResponsable;

class ViewResponsablesActivos extends Page
{
    protected static string $resource = SgsstResponsableResource::class;
    protected static string $view = 'filament.resources.sgsst-responsable.pages.cards-list';

    public $responsables;

    public function mount(): void
    {
        $user = auth()->user();

        abort_unless($user->hasRole('colaborador'), 403);

        $this->responsables = SgsstResponsable::with('user')
            ->where('activo', true)
            ->whereHas('user', fn($q) => $q->where('empresa_id', $user->empresa_id))
            ->get();
    }
}
