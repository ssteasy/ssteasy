<?php

namespace App\Filament\Resources\GestionCambioResource\Pages;

use App\Filament\Resources\GestionCambioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGestionCambios extends ListRecords
{
    protected static string $resource = GestionCambioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
