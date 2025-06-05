<?php

namespace App\Filament\Resources\RolResource\Pages;

use App\Filament\Resources\RolResource;
use Filament\Resources\Pages\EditRecord;

class EditRol extends EditRecord
{
    protected static string $resource = RolResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }

        return $data;
    }
}

