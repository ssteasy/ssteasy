<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Profile extends Page
{
    // Ahora acepta null, tal como lo define el padre
    protected static ?string $slug = 'profile';

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Cuenta';

    public $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    protected static string $view = 'filament.pages.profile';
}
