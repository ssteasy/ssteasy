<?php

namespace App\Filament\Resources\ProfesiogramaResource\Pages;

use App\Filament\Resources\ProfesiogramaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProfesiogramas extends ListRecords
{
    protected static string $resource = ProfesiogramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
