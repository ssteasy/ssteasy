<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\CurrentSgsstResponsables;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\View\PanelsRenderHook;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\NavigationGroup;      // ← Import añadido
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\ServiceProvider;
use App\Filament\Pages\MiEmpresa; 
use App\Filament\Widgets\MisCursosPendientesWidget;
use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\FilamentInfoWidget;
use App\Filament\Widgets\ProgresoColaboradoresWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->renderHook('panels::header.start', fn () => view('components.sidebar-toggle'))
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            // ↓↓↓ Definición de los grupos de navegación ↓↓↓
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Mi empresa')
                    ->icon('heroicon-s-office-building'),
                NavigationGroup::make()
                    ->label('Planear')
                    ->icon('heroicon-s-calendar'),
                NavigationGroup::make()
                    ->label('Hacer')
                    ->icon('heroicon-s-clipboard-document-list'),
            ])
            // ↑↑↑ Fin de navigationGroups ↑↑↑
            ->colors([
                'primary' => '#11456D',
            ])
            ->darkMode(false)
            ->brandName('SST Easy')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                MiEmpresa::class, 
            ])
            ->widgets([
                MisCursosPendientesWidget::class,
                ProgresoColaboradoresWidget::class,
                \App\Filament\Widgets\AdminCommitteesWidget::class,
                \App\Filament\Widgets\CollaboratorCommitteesWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
