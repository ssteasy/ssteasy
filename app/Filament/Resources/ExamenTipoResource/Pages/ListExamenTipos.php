<?php

namespace App\Filament\Resources\ExamenTipoResource\Pages;

use App\Filament\Resources\ExamenTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExamenTipos extends ListRecords
{
    protected static string $resource = ExamenTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
