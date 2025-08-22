<?php

namespace App\Filament\Resources\ExamenMedicoResource\Pages;

use App\Filament\Resources\ExamenMedicoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamenMedico extends EditRecord
{
    protected static string $resource = ExamenMedicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
