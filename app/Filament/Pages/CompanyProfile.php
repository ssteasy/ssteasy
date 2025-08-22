<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CompanyProfile extends Page
{
    // URL interna → /admin/company
    protected static ?string $slug = 'company';

    // Ícono y grupo en el menú (opcional)
    protected static ?string $navigationIcon  = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Cuenta';

    // Vista Blade que ya creaste
    protected static string $view = 'filament.pages.company';

    // Propiedad pública para usar en la vista
    public $empresa;

    public function mount(): void
{
    // Opción sencilla: sin eager-load extra
    $this->empresa = Auth::user()
        ->empresa()          // relación belongsTo
        ->firstOrFail();     // ← sin loadMissing()
}
}
