<?php

namespace App\Filament\Resources\EvaluacionEstandarMinimoResource\Pages;

use App\Filament\Resources\EvaluacionEstandarMinimoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvaluacionEstandarMinimos extends ListRecords
{
    protected static string $resource = EvaluacionEstandarMinimoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
