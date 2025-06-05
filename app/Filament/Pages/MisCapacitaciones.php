<?php

namespace App\Filament\Pages;

use App\Models\Capacitacion;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\Filament\Resources\CapacitacionResource\Pages\Curso;

class MisCapacitaciones extends Page implements HasTable
{
    use InteractsWithTable;
    
    /* navegación */
    protected static ?string $navigationIcon  = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Mis capacitaciones';
    protected static ?string $slug            = 'mis-capacitaciones';

    protected static ?string $navigationGroup  = 'Capacitaciones';
    protected static string $view = 'filament.pages.mis-capacitaciones';

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasRole('colaborador');
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole('colaborador');
    }
    public function table(Table $table): Table
    {
        $user = auth()->user();

        $query = Capacitacion::query()
            ->where('empresa_id', $user->empresa_id)
            ->where(function (EloquentBuilder $q) use ($user) {
                $q->where('tipo_asignacion', 'abierta')
                    ->orWhereHas(
                        'participantes',
                        fn($p) => $p->where('users.id', $user->id)
                    );
            });

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('nombre_capacitacion')
                    ->label('Curso')
                    ->wrap(),

                TextColumn::make('tipo_asignacion')
                    ->badge()
                    ->label('Tipo'),

                IconColumn::make('avance')
                    ->label('Progreso')
                    ->state(function (Capacitacion $r) use ($user) {
                        $total = $r->sesiones()->count();
                        $done  = $r->sesiones()
                            ->whereHas(
                                'usuarios',
                                fn($q) =>
                                $q->where('user_id', $user->id)->where('aprobado', true)
                            )->count();

                        return $total && $done === $total;  // check verde si 100 %
                    })
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->alignCenter(),

                TextColumn::make('pivot.estado')
                    ->label('Estado')
                    ->formatStateUsing(fn(?string $s) => $s ?? 'No inscrito'),
            ])
            ->actions([
                /* INSCRIBIRME: solo cursos abiertos y no inscrito */
                Actions\Action::make('inscribirse')
                    ->label('Inscribirme')
                    ->color('primary')
                    ->visible(
                        fn(Capacitacion $r) =>
                        $r->tipo_asignacion === 'abierta' &&
                            ! $r->participantes->contains($user->id)
                    )
                    ->action(function (Capacitacion $r) use ($user) {
                        $r->participantes()->attach(
                            $user->id,
                            ['estado' => 'en_progreso']
                        );
                        $this->notify('success', '¡Inscripción exitosa!');
                    }),

                Actions\Action::make('entrar')
                    ->label('Entrar')
                    ->icon('heroicon-o-arrow-right')
                    ->color('secondary')
                    ->visible(
                        fn(Capacitacion $r) =>
                        $r->participantes->contains($user->id)
                    )
                    ->url(
                        fn(Capacitacion $r) =>
                        // Pasamos el modelo completo; Filament resolverá el {record}
                        Curso::getUrl(['record' => $r])
                    ),

            ]);
    }
}
