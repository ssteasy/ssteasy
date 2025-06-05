<?php

namespace App\Filament\Resources\SgsstResponsableResource\Pages;

use App\Filament\Resources\SgsstResponsableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSgsstResponsable extends EditRecord
{
    protected static string $resource = SgsstResponsableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
