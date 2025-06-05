<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class FilamentThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::registerTheme(Vite::asset('resources/css/filament/theme.css'));
    }
}