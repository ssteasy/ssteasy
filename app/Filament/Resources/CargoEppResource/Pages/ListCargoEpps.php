<?php

namespace App\Filament\Resources\CargoEppResource\Pages;

use App\Filament\Resources\CargoEppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCargoEpps extends ListRecords
{
    protected static string $resource = CargoEppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
