<?php

namespace App\Filament\Resources\CapacitacionResource\Pages;

use App\Filament\Resources\CapacitacionResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;

class EditCapacitacion extends EditRecord
{
    protected static string $resource = CapacitacionResource::class;

    protected function afterSave(): void
    {
        if ($this->record->tipo_asignacion === 'masiva') {
            $state = $this->form->getState();
            $ids = User::forCurrentEmpresa()
                ->when($state['sede_ids'] ?? null, fn($q, $s) => $q->whereIn('sede_id', $s))
                ->when($state['cargo_ids'] ?? null, fn($q, $c) => $q->whereIn('cargo_id', $c))
                ->pluck('id')
                ->toArray();

            $this->record->participantes()->sync($ids);
        }
    }
}
