<?php

namespace App\Filament\Widgets;

use App\Models\Committee;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class AdminCommitteesWidget extends Widget
{
    protected static string $view = 'filament.widgets.admin-committees-widget';

    /** Solo admins y superadmins lo verán */
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    protected function getViewData(): array
    {
        $user = auth()->user();

        $query = Committee::query()
            ->withCount(['members', 'votes'])
            ->with(['members.user'])        // para ganador
            ->when(
                $user->hasRole('admin'),
                fn ($q) => $q->where('empresa_id', $user->empresa_id)
            );

        return [
            'committees' => $query->get()->map(fn (Committee $c) => $this->toPayload($c)),
        ];
    }

    /** Devuelve array con datos calculados */
    protected function toPayload(Committee $c): array
    {
        $now = now();
        $stage = match (true) {
            $now->between($c->fecha_inicio_inscripcion, $c->fecha_fin_inscripcion, true) => 'inscripcion',
            $now->between($c->fecha_inicio_votaciones,  $c->fecha_fin_votaciones,  true) => 'votacion',
            default => 'finalizado',
        };

        $winner = null;
        if ($stage === 'finalizado') {
            $winner = $c->members()
                ->withCount('votes')
                ->get()
                ->sortByDesc('votes_count')
                ->first();
        }

        return [
            'id'               => $c->id,
            'nombre'           => $c->nombre,
            'stage'            => $stage,
            'inscritos'        => $c->members_count,
            'totalVotos'       => $c->votes_count,
            'empresaVotantes'  => $c->empresa?->usuarios()->count() ?? 0,
            'winner'           => $winner,
        ];
    }
}
