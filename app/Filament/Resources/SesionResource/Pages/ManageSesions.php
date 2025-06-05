<?php

namespace App\Filament\Resources\SesionResource\Pages;

use App\Filament\Resources\SesionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSesions extends ManageRecords
{
    protected static string $resource = SesionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
