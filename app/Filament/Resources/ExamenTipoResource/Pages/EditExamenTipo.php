<?php

namespace App\Filament\Resources\ExamenTipoResource\Pages;

use App\Filament\Resources\ExamenTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamenTipo extends EditRecord
{
    protected static string $resource = ExamenTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
