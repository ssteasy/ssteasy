<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Page;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\URL;

class ViewUser extends Page
{
    protected static string $resource = UserResource::class;

    // Ruta bajo /admin/users/{record}/view
    protected static string $view = 'filament.resources.user-resource.pages.view-user';

    public function mount($record): void
    {
        $this->record = $record;
        $user = UserResource::getModel()::findOrFail($record);
        // Política: sólo superadmin o admin de la misma empresa
        abort_unless(
            auth()->user()->hasRole('superadmin')
            || (auth()->user()->hasRole('admin') && auth()->user()->empresa_id === $user->empresa_id),
            403
        );
        $this->user = $user;
    }

    protected function getActions(): array
    {
        return [
            Action::make('print')
                ->label('Descargar PDF')
                ->icon('heroicon-o-printer')
                ->url('javascript:window.print()'),
        ];
    }
}
