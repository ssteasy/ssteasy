<?php

namespace App\Filament\Resources\SedeResource\Pages;

use App\Filament\Resources\SedeResource;
use App\Models\Sede;
use Filament\Resources\Pages\Page;

class ShowSede extends Page
{
    protected static string $resource = SedeResource::class;
    protected static string $view     = 'filament.resources.sede-resource.pages.show-sede';

    public Sede $record;

    public function mount(Sede $record): void
    {
        $this->record = $record;
    }
}
