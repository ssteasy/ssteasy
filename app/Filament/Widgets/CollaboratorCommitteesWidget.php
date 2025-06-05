<?php

namespace App\Filament\Widgets;

use App\Models\Committee;
use Filament\Widgets\Widget;

class CollaboratorCommitteesWidget extends Widget
{
    protected static string $view = 'filament.widgets.collaborator-committees-widget';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('colaborador');
    }

    protected function getViewData(): array
    {
        $user = auth()->user();

        $committees = Committee::query()
            ->where('empresa_id', $user->empresa_id)
            ->with(['members', 'votes', 'members.user'])
            ->get()
            ->map(fn ($c) => $this->toPayload($c, $user->id));

        return ['committees' => $committees];
    }

    protected function toPayload(Committee $c, int $userId): array
    {
        $now = now();
        $stage = match (true) {
            $now->between($c->fecha_inicio_inscripcion, $c->fecha_fin_inscripcion, true) => 'inscripcion',
            $now->between($c->fecha_inicio_votaciones,  $c->fecha_fin_votaciones,  true) => 'votacion',
            default => 'finalizado',
        };

        $yaInscrito   = $c->members->contains('user_id', $userId);
        $yaVoto       = $c->votes->contains('voter_id', $userId);
        $winner       = null;

        if ($stage === 'finalizado') {
            $winner = $c->members
                ->sortByDesc(fn ($m) => $m->votes->count())
                ->first();
        }

        return compact('c', 'stage', 'yaInscrito', 'yaVoto', 'winner');
    }
}
