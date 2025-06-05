<?php
// app/Filament/Resources/SgsstResponsableResource/Pages/ListSgsstResponsables.php
namespace App\Filament\Resources\SgsstResponsableResource\Pages;

use App\Filament\Resources\SgsstResponsableResource;
use Filament\Resources\Pages\ListRecords;
use App\Models\SgsstResponsable;
use Illuminate\Contracts\View\View;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\HasPageActions;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Facades\FilamentView;
use Filament\Forms\Components\Actions\Action as FormAction;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Renderable;


class ListSgsstResponsables extends ListRecords
{
    protected static string $resource = SgsstResponsableResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
public function getHeader(): ?View
{
    if (auth()->user()->hasRole('superadmin')) {
        return null;
    }
    

    $responsable = SgsstResponsable::with(['user.cargo', 'user.rolPersonalizado', 'user.sede'])
        ->where('empresa_id', auth()->user()->empresa_id)
        ->where('activo', true)
        ->latest('fecha_inicio')
        ->first(); // ← solo uno


    return view('filament.resources.sgsst-responsables.partials.header-card', [
        'responsable' => $responsable, // ← objeto único
    ]);
}



}
