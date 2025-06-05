<?php

namespace App\Filament\Resources\CapacitacionResource\Pages;

use App\Filament\Resources\CapacitacionResource;
use Filament\Actions;                 // <— este “use” es el correcto
use Filament\Resources\Pages\ListRecords;

class ListCapacitacions extends ListRecords
{
    protected static string $resource = CapacitacionResource::class;

    protected function getHeaderActions(): array
    {
        // Cambia Tables\Actions\CreateAction por Actions\CreateAction
        return [
            Actions\CreateAction::make(),
        ];
    }
}
