<?php

namespace App\Filament\Resources\ProfesiogramaResource\Pages;

use App\Filament\Resources\ProfesiogramaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfesiograma extends EditRecord
{
    protected static string $resource = ProfesiogramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
