<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ...
    }

    public function boot(): void
    {
        Filament::serving(function () {
            /* ────────── Header personalizado ────────── */
            Filament::registerRenderHook(
                'layout.header.before',
                fn (): string => view('components.app-header')->render()
            );

            /* ────────── Sidebar personalizado ───────── */
            Filament::registerRenderHook(
                'layout.navigation.before',
                fn (): string => view('components.custom-sidebar')->render()
            );

            /* ────────── Banner de mantenimiento ─────── */
            Filament::registerRenderHook(
                'layout.header.after',
                fn (): string => auth()->check() && auth()->user()->hasRole('superadmin')
                    ? view('components.maintenance-banner')->render()
                    : ''
            );
        });
    }
}
