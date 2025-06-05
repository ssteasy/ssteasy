<?php

namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use App\Models\Vote;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;          // ✅
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class CommitteeVote extends Page
{
    use InteractsWithRecord, InteractsWithForms;         // ✅

    protected static string $resource = CommitteeResource::class;
    protected static string $view = 'filament.resources.committee.pages.vote';

    /* Campos de formulario */
    public ?int $choice = null;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(
            auth()->user()->hasRole('colaborador') &&
            now()->between(
                $this->record->fecha_inicio_votaciones->startOfDay(),
                $this->record->fecha_fin_votaciones->endOfDay(),
                true
            ),
            403
        );

        /** Opcional, si quieres valores por defecto */
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Radio::make('choice')
                ->label('Elige tu representante')
                ->options(function () {
                    return $this->record->members()
                        ->with('user')
                        ->get()
                        ->filter(fn($m) => $m->user)               // ⚠️ descarta si falta usuario
                        ->mapWithKeys(function ($m) {               // crea etiqueta segura
                            $user = $m->user;
                            $name = trim("{$user->primer_nombre} {$user->primer_apellido}") ?: "Usuario {$user->id}";
                            return [$m->id => $name];
                        })
                        ->toArray();
                })
                ->required()

        ];
    }

    public function vote(): void
    {
        $this->form->validate();

        Vote::updateOrCreate(
            [
                'committee_id' => $this->record->id,
                'voter_id' => auth()->id(),
            ],
            [
                'committee_member_id' => $this->choice,
            ]
        );

        Notification::make()
            ->title('¡Voto registrado!')
            ->success()
            ->send();
    }
}
