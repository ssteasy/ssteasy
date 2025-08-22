<?php

namespace App\Filament\Resources\PlanTrabajoAnualResource\Pages;

use App\Filament\Resources\PlanTrabajoAnualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PlanTrabajoAnualResource\Widgets\PlanTrabajoCalendarWidget;

class ListPlanTrabajoAnuals extends ListRecords
{
    protected static string $resource = PlanTrabajoAnualResource::class;

    /* ─── widgets ─── */
    protected function getHeaderWidgets(): array
    {
        return [
            PlanTrabajoCalendarWidget::class,
        ];
    }

    /* Distribución de la grid para los header-widgets */
    protected static int|array $headerWidgetsColumns = 1;

    /* Acciones */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

      public function getHeaderWidgetsColumns(): int|array
      {
          return [
              'default' => 1,
              'lg'      => 1,
              'xl'      => 1,
              '2xl'     => 1,
          ];
      }
}
