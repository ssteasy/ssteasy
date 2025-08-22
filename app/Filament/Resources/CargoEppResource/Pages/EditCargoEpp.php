<?php

namespace App\Filament\Resources\CargoEppResource\Pages;

use App\Filament\Resources\CargoEppResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCargoEpp extends EditRecord
{
    protected static string $resource = CargoEppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
