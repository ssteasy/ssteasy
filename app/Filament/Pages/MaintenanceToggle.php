<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Filament\Forms\Components\Toggle;
use Filament\Forms;
use Filament\Notifications\Notification;   // ← IMPORTA ESTO

class MaintenanceToggle extends Page
{
    // … (propiedades navigation) …

    protected static string $view = 'filament.pages.maintenance-toggle';

    public ?bool $maintenance_on = false;

    public static function canAccess(): bool
    {
        return Filament::auth()->user()?->hasRole('superadmin');
    }

    public function mount(): void
    {
        $this->maintenance_on = app()->isDownForMaintenance();
    }

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('maintenance_on')
                ->label('Sitio en mantenimiento')
                ->inline(false),
        ];
    }

    public function save(): void
    {
        if ($this->maintenance_on) {
            Artisan::call('down', ['--render' => 'errors::503']);
        } else {
            Artisan::call('up');
        }

        /* NOTIFICACIÓN corregida */
        Notification::make()
            ->success()
            ->title('Estado actualizado')
            ->body(
                $this->maintenance_on
                    ? 'El sitio está ahora en modo mantenimiento.'
                    : 'El sitio está nuevamente en línea.'
            )
            ->send();
    }
}
