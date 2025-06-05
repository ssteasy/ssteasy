<?php

namespace App\Providers;

use Filament\PanelProvider;

class FilamentPanelProvider extends PanelProvidert;
{
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerRenderHook(
                'head.start',
                fn (): string => '<link rel="stylesheet" href="https://app.ssteasy.com/css/app.css" />'
            );

            Filament::registerNavigationGroups([
                'Gestión SST',
                'Usuarios',
            ]);

            Filament::brandLogo(fn () => asset('storage/img/logo.png'));
            Filament::brandLogoHeight('2rem');
        });
    }
}
