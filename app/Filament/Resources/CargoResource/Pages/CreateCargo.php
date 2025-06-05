<?php

namespace App\Filament\Resources\CargoResource\Pages;

use App\Filament\Resources\CargoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCargo extends CreateRecord
{
    protected static string $resource = CargoResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }

        return $data;
    }
}
