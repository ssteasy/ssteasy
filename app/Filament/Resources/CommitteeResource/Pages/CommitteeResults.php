<?php
namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use App\Models\CommitteeMember;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
// 👇 Importa el trait
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class CommitteeResults extends Page
{
    // 👇 Añade aquí también InteractsWithRecord
    use InteractsWithForms, InteractsWithRecord;

    protected static string $resource = CommitteeResource::class;
    protected static string $view     = 'filament.resources.committee.pages.results';

    public array $winners = [];

    public function mount(int|string $record): void
    {
        // Ahora resolveRecord existe gracias al trait
        $this->record = $this->resolveRecord($record);

        // Cargar ganadores actuales como default
        $this->winners = $this->record
            ->members()
            ->where('activo', true)
            ->pluck('id')
            ->toArray();

        $this->form->fill(['winners' => $this->winners]);
    }

    protected function getFormSchema(): array
    {
        // Sólo permitir al admin:
        abort_unless(auth()->user()->hasAnyRole(['admin', 'superadmin']), 403);

        // CheckboxList con todos los miembros y sus votos
        $options = $this->record->members()
            ->with('user')
            ->withCount('votes')
            ->get()
            ->mapWithKeys(fn($m) => [
                $m->id => "{$m->user->primer_nombre} {$m->user->primer_apellido} ({$m->votes_count} votos)",
            ])
            ->toArray();

        return [
            CheckboxList::make('winners')
                ->label('Marcar ganadores')
                ->options($options)
                ->helperText('Selecciona uno o varios miembros para marcarlos como activos en el comité'),
        ];
    }

    protected function getFormModel(): string
    {
        return CommitteeMember::class;
    }

    public function saveWinners(): void
    {
        abort_unless(auth()->user()->hasAnyRole(['admin', 'superadmin']), 403);

        $data = $this->form->getState();

        // Marcar todos según selección
        foreach ($this->record->members as $member) {
            $member->activo = in_array($member->id, $data['winners']);
            $member->save();
        }

        Notification::make()
            ->title('Resultados guardados')
            ->success()
            ->body('Los ganadores han sido actualizados correctamente.')
            ->send();
    }

    public function getActions(): array
    {
        return [
            \Filament\Pages\Actions\Action::make('saveWinners')
                ->label('Guardar ganadores')
                ->action('saveWinners')
                ->button()
                ->color('primary'),
        ];
    }

    /** Devuelve [ ['name' => 'Ana', 'votes' => 3], … ] */
    public function getChartData(): array
    {
        return $this->record->members()
            ->with('user')
            ->withCount('votes')
            ->get()
            ->map(fn($m) => [
                'name'  => trim("{$m->user->primer_nombre} {$m->user->primer_apellido}") ?: "Miembro {$m->id}",
                'votes' => $m->votes_count,
            ])
            ->sortByDesc('votes')
            ->values()
            ->all();
    }
}
