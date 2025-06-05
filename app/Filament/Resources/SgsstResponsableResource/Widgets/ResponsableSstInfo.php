<?php
namespace App\Filament\Resources\SgsstResponsableResource\Widgets;

use App\Models\SgsstResponsable;
use Filament\Widgets\Widget;
use Filament\Resources\Widgets\ResourceWidget;


class ResponsableSstInfo extends Widget
{
    protected static string $view = 'filament.resources.sgsst-responsable-resource.widgets.responsable-sst-info';

    protected function getData(): array
    {
        if (auth()->user()->hasRole('superadmin')) {
            return ['responsable' => null];
        }

        $responsable = \App\Models\SgsstResponsable::with(['user.cargo', 'user.rolPersonalizado', 'user.sede'])
            ->whereHas('user', fn ($q) => $q->where('empresa_id', auth()->user()->empresa_id))
            ->whereNull('fecha_fin')
            ->first();

        return [
            'responsable' => $responsable,
        ];
    }

    public function isVisible(): bool
    {
        return auth()->check() && !auth()->user()->hasRole('superadmin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // evita que aparezca en la barra lateral
    }
}
