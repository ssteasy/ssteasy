<?php

namespace App\Filament\Resources\GestionCambioResource\Pages;

use App\Filament\Resources\GestionCambioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGestionCambio extends EditRecord
{
    protected static string $resource = GestionCambioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
