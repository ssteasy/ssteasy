<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\URL;

class TinkerConsole extends Page
{
    public static string $view = 'filament.pages.tinker-console';
    public static ?string $navigationGroup = 'Desarrollo';
    public static ?string $navigationLabel = 'Web Tinker';

    public array $presets = [
        'Contar Usuarios' => '\\App\\Models\\User::count();',
        'Ver Entorno'     => "config('app.env');",
        'Fecha Actual'    => 'now();',
    ];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('acceder web-tinker');
    }

    public function mount(): void
    {
        $this->authorize('viewWebTinker');
    }
}