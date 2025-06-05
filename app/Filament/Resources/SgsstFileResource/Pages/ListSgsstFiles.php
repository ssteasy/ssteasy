<?php

namespace App\Filament\Resources\SgsstFileResource\Pages;

use App\Filament\Resources\SgsstFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSgsstFiles extends ListRecords
{
    protected static string $resource = SgsstFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
