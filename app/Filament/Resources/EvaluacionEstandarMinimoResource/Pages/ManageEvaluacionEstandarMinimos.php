<?php

namespace App\Filament\Resources\EvaluacionEstandarMinimoResource\Pages;

use App\Filament\Resources\EvaluacionEstandarMinimoResource;
use Filament\Resources\Pages\ListRecords;

class ManageEvaluacionEstandarMinimos extends ListRecords
{
    protected static string $resource = EvaluacionEstandarMinimoResource::class;

    protected function getHeaderWidgets(): array
    {
        return static::$resource::getWidgets();
    }
}
