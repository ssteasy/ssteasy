<?php

namespace App\Filament\Resources\PlanTrabajoAnualResource\Pages;

use App\Filament\Resources\PlanTrabajoAnualResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlanTrabajoAnual extends ViewRecord
{
    protected static string $resource = PlanTrabajoAnualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
