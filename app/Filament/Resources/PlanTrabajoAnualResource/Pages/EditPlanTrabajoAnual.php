<?php

namespace App\Filament\Resources\PlanTrabajoAnualResource\Pages;

use App\Filament\Resources\PlanTrabajoAnualResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanTrabajoAnual extends EditRecord
{
    protected static string $resource = PlanTrabajoAnualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
