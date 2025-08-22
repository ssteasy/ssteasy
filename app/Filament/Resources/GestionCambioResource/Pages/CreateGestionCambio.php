<?php

namespace App\Filament\Resources\GestionCambioResource\Pages;

use App\Filament\Resources\GestionCambioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGestionCambio extends CreateRecord
{
    protected static string $resource = GestionCambioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creado_por'] = auth()->id();
        if (! auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }
        return $data;
    }
}
