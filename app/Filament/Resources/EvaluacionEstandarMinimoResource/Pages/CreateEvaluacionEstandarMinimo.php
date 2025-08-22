<?php

namespace App\Filament\Resources\EvaluacionEstandarMinimoResource\Pages;

use App\Filament\Resources\EvaluacionEstandarMinimoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluacionEstandarMinimo extends CreateRecord
{
    protected static string $resource = EvaluacionEstandarMinimoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Fuerza los valores correctos
        $data['empresa_id'] = auth()->user()->empresa_id;
        $data['year']       = now()->year;

        return $data;
    }
}
