<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommitteeResource\Pages;
use App\Filament\Resources\CommitteeResource;
use Filament\Notifications\Notification;
use App\Filament\Resources\CommitteeResource\RelationManagers;
use App\Models\Committee;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;
class CommitteeResource extends Resource
{
    protected static ?string $model = Committee::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Comités';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin', 'colaborador']);
    }


    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin', 'colaborador']);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('admin')) {
            return $query->where('empresa_id', auth()->user()->empresa_id);
        }

        return $query; // Superadmin ve todo
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sección 1: Datos básicos del comité
                Forms\Components\Section::make('Datos del Comité')
                    ->schema([

                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre del Comité')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('objetivo')
                            ->label('Objetivo')
                            ->columnSpanFull()
                            ->nullable(),

                        Forms\Components\DatePicker::make('fecha_inicio_inscripcion')
                            ->label('Fecha Inicio Inscripción')
                            ->required(),

                        Forms\Components\DatePicker::make('fecha_fin_inscripcion')
                            ->label('Fecha Fin Inscripción')
                            ->required(),

                        Forms\Components\DatePicker::make('fecha_inicio_votaciones')
                            ->label('Fecha Inicio Votaciones')
                            ->required(),

                        Forms\Components\DatePicker::make('fecha_fin_votaciones')
                            ->label('Fecha Fin Votaciones')
                            ->required(),
                        Forms\Components\Hidden::make('empresa_id')
                            ->default(auth()->user()->empresa_id),
                    ])->columns(2),

                // Sección 2: Inscritos en el comité
                Forms\Components\Section::make('Miembros del Comité')
                    ->schema([
                        Forms\Components\Repeater::make('members')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Usuario')
                                    ->options(
                                        fn() => \App\Models\User::query()
                                            ->where('empresa_id', auth()->user()->empresa_id)
                                            ->pluck('primer_nombre', 'id')          // 👈  devuelve array [id => nombre]
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('tipo_representante')
                                    ->options([
                                        'representante_colaboradores' => 'Representante de Colaboradores',
                                        'representante_trabajador' => 'Representante del Trabajador',
                                    ])
                                    ->required(),

                                Forms\Components\Textarea::make('rol_en_comite')
                                    ->label('Rol en el Comité'),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->addActionLabel('Agregar Miembro (Opcional)')
                            ->defaultItems(0)
                    ])
            ]);
    }

    // Tabla de listado
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_inicio_votaciones')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_fin_votaciones')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('members_count')
                    ->label('Inscritos')
                    ->counts('members'),
            ])
            // 1) Panel expandible con info extra

            ->filters([
                Tables\Filters\SelectFilter::make('empresa_id')
                    ->relationship('empresa', 'nombre')
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
            ])
            ->actions([
                Tables\Actions\Action::make('detalles')
                    ->label('Detalles')
                    ->icon('heroicon-o-information-circle')
                    ->color('secondary')
                    ->modalHeading(fn(Committee $record) => "Detalles de “{$record->nombre}”")
                    ->modalContent(fn(Committee $record) => view('filament.resources.committee.pages.details', [
                        'record' => $record,
                    ]))
                    ->visible(
                        fn(Committee $record) =>
                        // Superadmins y admins siempre pueden ver “Detalles”
                        auth()->user()->hasAnyRole(['superadmin', 'admin'])
                        ||
                            // Colaboradores solo mientras la votación no haya terminado
                        (
                            auth()->user()->hasRole('colaborador')
                            && Date::now()->lessThanOrEqualTo($record->fecha_fin_votaciones->endOfDay())
                        )
                    ),
                // Inscribirse
                Tables\Actions\Action::make('inscribirse')
                    ->label('Postularme')
                    ->icon('heroicon-o-user-plus')
                    ->requiresConfirmation()
                    ->visible(
                        fn(Committee $record) =>
                        auth()->user()->hasRole('colaborador')
                        && now()->between($record->fecha_inicio_inscripcion, $record->fecha_fin_inscripcion)
                        && !$record->members()->where('user_id', auth()->id())->exists()
                    )
                    ->action(function (Committee $record) {
                        $record->members()->create([
                            'user_id' => auth()->id(),
                            'tipo_representante' => 'representante_trabajador',
                            'activo' => true,
                        ]);
                        Notification::make()
                            ->title('¡Inscripción exitosa!')
                            ->success()
                            ->send();
                    }),

                // Votar
                Tables\Actions\Action::make('votar')
                    ->label('Votar')
                    ->icon('heroicon-o-pencil-square')
                    ->url(
                        fn(Committee $record) =>
                        CommitteeResource::getUrl('vote', ['record' => $record])
                    )
                    ->visible(
                        fn(Committee $record) =>
                        auth()->user()->hasRole('colaborador')
                        && now()->between(
                            $record->fecha_inicio_votaciones->startOfDay(),
                            $record->fecha_fin_votaciones->endOfDay(),
                            true
                        )
                    ),

                // Resultados (solo admin y después de la votación)
                Tables\Actions\Action::make('resultados')
                    ->icon('heroicon-o-chart-bar')
                    ->url(
                        fn(Committee $record) =>
                        CommitteeResource::getUrl('results', ['record' => $record])
                    )
                    ->visible(
                        fn(Committee $record) =>
                        auth()->user()->hasRole('admin')
                        && now()->isAfter($record->fecha_fin_votaciones->endOfDay())
                    ),
                Tables\Actions\Action::make('miembros')
                    ->label('Ver miembros')
                    ->icon('heroicon-o-users')
                    ->url(fn($record) => CommitteeResource::getUrl('members', ['record' => $record]))
                    // Visible solo a colaboradores (o admins) y cuando ya haya ganadores:
                    ->visible(
                        fn($record) =>
                        auth()->user()->hasRole('colaborador')
                        && $record->members()->where('activo', true)->exists()
                    )
                    ->color('secondary'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    // Página de resultados
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommittees::route('/'),
            'create' => Pages\CreateCommittee::route('/create'),
            'edit' => Pages\EditCommittee::route('/{record}/edit'),
            'results' => Pages\CommitteeResults::route('/{record}/results'), // Nueva ruta
            'vote' => Pages\CommitteeVote::route('/{record}/vote'),
            'members' => Pages\CommitteeMembers::route('/{record}/members'),
        ];
    }
}