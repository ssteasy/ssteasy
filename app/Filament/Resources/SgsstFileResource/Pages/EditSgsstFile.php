<?php

namespace App\Filament\Resources\SgsstFileResource\Pages;

use App\Filament\Resources\SgsstFileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSgsstFile extends EditRecord
{
    protected static string $resource = SgsstFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
