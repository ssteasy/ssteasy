<?php

namespace App\Filament\Resources\EncuestaResource\RelationManagers;

use App\Models\User;
use App\Models\Cargo;
use App\Models\Sede;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components as F;
use Filament\Facades\Filament;

class UsuariosRelationManager extends RelationManager
{
    protected static string $relationship = 'usuarios';
    protected static ?string $recordTitleAttribute = 'primer_nombre';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('primer_nombre')->label('Nombre'),
                TextColumn::make('cedula')->label('Cédula'),
                TextColumn::make('cargo.nombre')->label('Cargo'),
                TextColumn::make('sede.nombre')->label('Sede'),

                BadgeColumn::make('pivot.respondida')
                    ->label('Respondida')
                    ->colors([
                        'success' => fn (bool $state): bool => $state,
                        'danger'  => fn (bool $state): bool => ! $state,
                    ]),

                TextColumn::make('pivot.respondido_at')
                    ->label('Fecha respuesta')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->filters([
                SelectFilter::make('cargo')
                    ->relationship('cargo', 'nombre'),
                SelectFilter::make('sede')
                    ->relationship('sede', 'nombre'),
                Filter::make('respondida')
                    ->label('Sólo respondidas')
                    ->query(fn (Builder $query) => $query->wherePivot('respondida', true)),
            ])
            ->headerActions([
                AttachAction::make('asignar')
                    ->label('Asignar Usuarios')
                    ->visible(fn () => Filament::auth()->user()->hasAnyRole(['admin','superadmin']))
                    ->form([
                        F\Select::make('users')
                            ->label('Usuarios individuales')
                            ->multiple()
                            ->options(User::where('empresa_id', auth()->user()->empresa_id)->pluck('primer_nombre', 'id'))
                            ->searchable()
                            ->helperText('Busca por nombre o cédula'),
                        F\Select::make('cargo_id')
                            ->label('Por Cargo')
                            ->multiple()
                            ->options(Cargo::where('empresa_id', auth()->user()->empresa_id)->pluck('nombre','id'))
                            ->searchable(),
                        F\Select::make('sede_id')
                            ->label('Por Sede')
                            ->multiple()
                            ->options(Sede::where('empresa_id', auth()->user()->empresa_id)->pluck('nombre','id'))
                            ->searchable(),
                    ])
                    ->action(function (array $data) {
                        $encuesta = $this->getOwnerRecord();

                        if (! empty($data['users'])) {
                            $encuesta->usuarios()->syncWithoutDetaching($data['users']);
                        }
                        if (! empty($data['cargo_id'])) {
                            $ids = User::whereIn('cargo_id', $data['cargo_id'])
                                       ->where('empresa_id', auth()->user()->empresa_id)
                                       ->pluck('id');
                            $encuesta->usuarios()->syncWithoutDetaching($ids);
                        }
                        if (! empty($data['sede_id'])) {
                            $ids = User::whereIn('sede_id', $data['sede_id'])
                                       ->where('empresa_id', auth()->user()->empresa_id)
                                       ->pluck('id');
                            $encuesta->usuarios()->syncWithoutDetaching($ids);
                        }
                    })
                    ->modalWidth('lg'),
            ])
            ->actions([
                Action::make('verRespuestas')
                    ->label('Ver respuestas')
                    ->icon('heroicon-o-eye')
                    ->visible(fn (User $user) => $user->pivot->respondida)
                    ->modalHeading(fn (User $user) => "Respuestas de {$user->primer_nombre}")
                    ->modalWidth('lg')
                    ->modalContent(function (User $user): View {
                        // 1) Re-obtenemos el modelo pivot completo
                        $encuesta = $this->getOwnerRecord();
                        $usuario  = $encuesta
                            ->usuarios()
                            ->where('users.id', $user->id)
                            ->firstOrFail();

                        // 2) Devolvemos el View (no un string)
                        return view('encuestas.respuestas-modal', [
                            'encuesta' => $encuesta,
                            'usuario'  => $usuario,
                        ]);
                    }),

                DetachAction::make()->label('Quitar'),
            ]);
    }
}

