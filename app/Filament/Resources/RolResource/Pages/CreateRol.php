<?php

namespace App\Filament\Resources\RolResource\Pages;

use App\Filament\Resources\RolResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRol extends CreateRecord
{
    protected static string $resource = RolResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Si no soy superadmin, fuerzo empresa_id a la del usuario
        if (! auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }

        return $data;
    }
}
