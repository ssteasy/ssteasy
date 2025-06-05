<?php

namespace App\Filament\Resources\CapacitacionResource\RelationManagers;

use App\Models\Sesion;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ParticipantesRelationManager extends RelationManager
{
    protected static string $relationship = 'participantes'; // pivot capacitacion_user
    protected static ?string $label = 'Participantes';

    public function canCreate(): bool
    {
        return false;
    }

    public function canAttach(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('primer_nombre')
                    ->label('Colaborador')
                    ->searchable()
                    ->formatStateUsing(
                        fn (?string $state, User $record) => "{$record->primer_nombre} {$record->primer_apellido}"
                    ),

                // ParticipantesRelationManager.php
                

                TextColumn::make('estado')
                    ->label('Estado')
                    ->getStateUsing(function (Model $record) {           // ← 1er argumento: el registro
                        /** @var \App\Models\User $record */
                
                        $curso        = $this->getOwnerRecord();
                        $total        = $curso->sesiones()->count();
                        $completadas  = $curso->sesiones()
                            ->whereHas('usuarios', fn ($q) =>
                                $q->where('user_id', $record->id)->where('aprobado', true)
                            )
                            ->count();
                
                        return $total && $completadas === $total ? 'completado' : 'pendiente';
                    })
                    ->badge()
                    ->colors([
                        'primary'  => 'pendiente',
                        'success'  => 'completado',
                    ]),
                

                TextColumn::make('clases_completadas')
                    ->label('Clases completadas')
                    ->getStateUsing(
                        fn(User $user) =>
                        Sesion::query()
                            ->where('capacitacion_id', $this->getOwnerRecord()->id)
                            ->whereHas(
                                'usuarios',
                                fn($q) =>
                                $q->where('user_id', $user->id)
                                    ->where('aprobado', true)
                            )
                            ->count()
                    ),

                TextColumn::make('nota_promedio')
                    ->label('Nota (%)')
                    ->formatStateUsing(
                        fn(?float $state) =>
                        $state !== null
                        ? "{$state}%"
                        : '—'
                    )
                    ->getStateUsing(fn(User $user) => collect(
                        Sesion::query()
                            ->where('capacitacion_id', $this->getOwnerRecord()->id)
                            ->whereHas(
                                'usuarios',
                                fn($q) =>
                                $q->where('user_id', $user->id)
                                    ->whereNotNull('score')
                            )
                            ->get()
                            ->map(
                                fn(Sesion $sesion) =>
                                optional(
                                    $sesion->usuarios
                                        ->firstWhere('id', $user->id)
                                )->pivot
                                    ->score
                            )
                            ->filter()
                    )
                        ->whenNotEmpty(fn($scores) => round($scores->avg(), 2))),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Asignar colaborador')
                    ->visible(
                        fn() =>
                        $this->getOwnerRecord()->tipo_asignacion === 'manual'
                    ),

                Action::make('asignar_todos')
                    ->label('Asignar a todos')
                    ->icon('heroicon-o-users')
                    ->requiresConfirmation()
                    ->visible(
                        fn() =>
                        $this->getOwnerRecord()->tipo_asignacion === 'obligatoria'
                    )
                    ->action(function () {
                        $curso = $this->getOwnerRecord();
                        $userIds = User::where('empresa_id', $curso->empresa_id)
                            ->pluck('id');

                        $curso->participantes()->syncWithoutDetaching(
                            $userIds->mapWithKeys(fn($id) => [
                                $id => ['estado' => 'pendiente'],
                            ])->toArray()
                        );

                        Filament::notify('success', 'Asignados todos los colaboradores');
                    }),
            ])
            ->actions([
                Action::make('desbloquear_sesiones')
        ->label('Reabrir Sesiones')
        ->icon('heroicon-o-lock-open')
        ->requiresConfirmation()
        ->action(function (User $record) {          // ← o function ($record)
            $userId  = $record->id;
            $sesiones = $this->getOwnerRecord()->sesiones;

            foreach ($sesiones as $sesion) {
                $sesion->usuarios()->updateExistingPivot($userId, [
                    'aprobado'       => false,
                    'score'          => null,
                    'respuesta_json' => null,
                    'completado_at'  => null,
                ]);
            }

            Notification::make()
    ->title('Sesiones reabiertas para el colaborador.')
    ->success()
    ->send();
        })
        ->visible(fn () => $this->getOwnerRecord()->tipo_asignacion !== 'manual'),
                Action::make('ver_respuestas')
                    ->label('Ver respuestas')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(
                        fn(User $user) =>
                        "Respuestas de {$user->primer_nombre} {$user->primer_apellido}"
                    )
                    ->modalWidth('3xl')
                    ->form([
                        Repeater::make('respuestas')
                            ->disableLabel()
                            ->columns(1)
                            ->schema([
                                TextInput::make('sesion')
                                    ->label('Clase')
                                    ->disabled(),

                                Textarea::make('respuesta')
                                    ->label('Respuesta (JSON)')
                                    ->rows(4)
                                    ->disabled(),
                            ])
                            ->default(
                                fn(User $user) =>
                                Sesion::query()
                                    ->where('capacitacion_id', $this->getOwnerRecord()->id)
                                    ->get()
                                    ->map(fn(Sesion $sesion) => [
                                        'sesion' => $sesion->titulo,
                                        'respuesta' => json_encode(
                                            optional(
                                                $sesion->usuarios
                                                    ->firstWhere('id', $user->id)
                                            )->pivot
                                                ->respuesta_json
                                        ),
                                    ])->toArray()
                            ),
                    ])
                    ->modalActions([]),

                Action::make('desasignar')
                    ->label('Desasignar')
                    ->icon('heroicon-o-trash')
                    ->action(
                        fn(Model $record) =>
                        $this->getOwnerRecord()->participantes()->detach($record->id)
                    )
                    ->visible(
                        fn() =>
                        $this->getOwnerRecord()->tipo_asignacion === 'manual'
                    ),

            ]);
    }
}
