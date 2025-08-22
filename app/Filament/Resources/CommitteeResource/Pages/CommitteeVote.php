<?php

namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use App\Models\Vote;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class CommitteeVote extends Page
{
    use InteractsWithRecord;

    // ↑ Ya no necesitamos InteractsWithForms, pues validamos manualmente
    protected static string $resource = CommitteeResource::class;
    protected static string $view     = 'filament.resources.committee.pages.vote';

    public ?int $choice       = null;
    public bool $alreadyVoted = false;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(
            auth()->user()->hasRole('colaborador')
            && now()->between(
                $this->record->fecha_inicio_votaciones->startOfDay(),
                $this->record->fecha_fin_votaciones->endOfDay(),
                true
            ),
            403
        );

        // 1) Detectar voto existente
        $existing = Vote::where('committee_id', $this->record->id)
                        ->where('voter_id', auth()->id())
                        ->first();

        if ($existing) {
            $this->choice       = $existing->committee_member_id;
            $this->alreadyVoted = true;
        }
    }

    public function vote(): void
    {
        // 2) Si ya votó, sólo mostramos aviso
        if ($this->alreadyVoted) {
            Notification::make()
                ->title('Ya has votado')
                ->warning()
                ->body('Tu voto ya fue registrado y no puede modificarse.')
                ->send();

            return;
        }

        // 3) Validaciones
        $this->validate([
            'choice' => ['required', 'integer'],
        ]);

        // 4) Crear el voto (sin updateOrCreate para evitar cambios)
        Vote::create([
            'committee_id'         => $this->record->id,
            'committee_member_id'  => $this->choice,
            'voter_id'             => auth()->id(),
        ]);

        $this->alreadyVoted = true;

        Notification::make()
            ->title('¡Voto registrado!')
            ->success()
            ->send();
    }
}
