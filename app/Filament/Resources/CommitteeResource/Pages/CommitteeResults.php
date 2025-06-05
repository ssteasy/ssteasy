<?php

namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class CommitteeResults extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CommitteeResource::class;
    protected static string $view = 'filament.resources.committee.pages.results';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    /** Devuelve [ ['name' => 'Ana', 'votes' => 3], … ] */
    public function getChartData(): array
    {
        return $this->record->members()
            ->with('user')                  // ← Carga la relación
            ->withCount('votes')
            ->get()
            ->map(fn($m) => [
                'name' => $m->user?->full_name ?? "Miembro {$m->id}", // respaldo
                'votes' => $m->votes_count,
            ])
            ->sortByDesc('votes')
            ->values()
            ->all();
    }
}
