<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CapacitacionResource\Pages;
use App\Filament\Resources\CapacitacionResource\RelationManagers;
use App\Filament\Resources\CapacitacionResource\Pages\Curso;
use App\Models\Capacitacion;
use App\Models\User;

use Filament\Forms\Components\{FileUpload, DatePicker, Select, MultiSelect};
use Malzariey\FilamentLexicalEditor\FilamentLexicalEditor;
use Filament\Forms\{Components as F, Form};
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\{Actions, Columns as T, Table};
use Filament\Tables\Filters\TernaryFilter;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;






class CapacitacionResource extends Resource
{
    protected static ?string $model = Capacitacion::class;

    protected static ?string $navigationLabel = 'Cursos';
    protected static ?string $navigationGroup = 'Gestión del conocimiento';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                /** Empresa — oculto, forzado al ID del usuario */
                F\Hidden::make('empresa_id')
                    ->default(fn() => Filament::auth()->user()->empresa_id)
                    ->dehydrated()
                    ->required(),

                /** Creador (user_id) */
                F\Hidden::make('created_by')
                    ->default(fn() => Filament::auth()->id())
                    ->dehydrated(),

                F\TextInput::make('codigo_capacitacion')
                    ->unique(ignoreRecord: true)
                    ->required(),

                TextInput::make('categoria')
                    ->label('Categoría')
                    ->placeholder('Selecciona o escribe...')
                    ->datalist(fn () => Capacitacion::query()
                        ->whereNotNull('categoria')
                        ->where('categoria', '!=', '')
                        ->distinct()
                        ->orderBy('categoria')
                        ->pluck('categoria')
                        ->toArray()
                    )
                    ->columnSpan(1),

                F\TextInput::make('nombre_capacitacion')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('miniatura')
                    ->label('Miniatura (imagen)')
                    ->image()
                    ->directory('capacitaciones/miniaturas')
                    ->maxSize(1024)    // en KB, opcional
                    ->nullable()
                    ->columnSpanFull(),

                DatePicker::make('fecha_inicio')
                    ->label('Fecha de inicio (opcional)')
                    ->nullable()
                    ->columnSpan(1),

                DatePicker::make('fecha_fin')
                    ->label('Fecha fin (opcional)')
                    ->nullable()
                    ->columnSpan(1),

                FilamentLexicalEditor::make('objetivo')
                    ->label('Descripción / Objetivo')
                    ->required(),

                F\Select::make('tipo_asignacion')
                    ->label('Modo de asignación')
                    ->options([
                        'manual' => 'Asignación manual',
                        'abierta' => 'Curso abierto (colaborador se inscribe)',
                        'obligatoria' => 'Obligatoria para todos',
                    ])
                    ->default('manual')
                    ->required()
                    ->reactive()          // ← clave para que Filament reevalúe dependientes
                    ->columnSpan(2),
                MultiSelect::make('participantes')
                    ->label('Colaboradores asignados')

                    ->relationship('participantes', 'id')
                    ->options(
                        fn() =>
                        User::where('empresa_id', Filament::auth()->user()->empresa_id)
                            ->get()
                            ->mapWithKeys(fn($u) => [
                                $u->id => "{$u->primer_nombre} {$u->primer_apellido} – {$u->numero_documento}"
                            ])
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn(User $u) =>
                        "{$u->primer_nombre} {$u->primer_apellido} – {$u->numero_documento}"
                    )
                    ->preload()
                    ->searchable()

                    /* --- reglas que ahora sí se reevaluarán --- */
                    ->required(fn(Get $get) => $get('tipo_asignacion') === 'manual')
                    ->dehydrated(fn(Get $get) => $get('tipo_asignacion') === 'manual')
                    ->hidden(fn(Get $get) => $get('tipo_asignacion') !== 'manual')
                    /* ------------------------------------------ */

                    ->columnSpanFull(),

            ])
            ->columns(1);
    }

    /* ---------------------------------------------------------------- TABLE */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('fecha_inicio', 'desc')
            ->columns([
                T\TextColumn::make('codigo_capacitacion')
                    ->searchable()
                    ->sortable(),

                T\TextColumn::make('categoria')
                    ->label('Categoría')
                    ->sortable()
                    ->searchable(),

                T\TextColumn::make('nombre_capacitacion')
                    ->wrap()
                    ->limit(40)
                    ->tooltip(fn(Capacitacion $c) => $c->nombre_capacitacion),

                T\BadgeColumn::make('empresa.nombre')
                    ->color('info')
                    ->visible(fn() => Filament::auth()->user()->hasRole('superadmin')),

                T\TextColumn::make('creador.name')
                    ->label('Creada por')
                    ->searchable()
                    ->visible(fn() => Filament::auth()->user()->hasRole('superadmin')),

                T\TextColumn::make('fecha_inicio')->date()->sortable(),
                T\TextColumn::make('fecha_fin')->date()->sortable(),
                T\BooleanColumn::make('activa')->label('Activa'),
            ])
            ->filters([
                TernaryFilter::make('activa'),
                SelectFilter::make('categoria')
                    ->label('Categoría')
                    ->options(
                        fn() => Capacitacion::query()
                            ->pluck('categoria', 'categoria')
                            ->filter()         // elimina null/strings vacíos
                            ->unique()
                            ->toArray()
                    ),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\Action::make('gestionar_sesiones')
                    ->label('Sesiones')                   // texto del botón
                    ->icon('heroicon-o-building-library')       // ícono (📚)
                    ->color('secondary')                  // opcional: color Filament
                    ->visible(
                        fn() =>                   // solo admins / superadmins
                        Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])
                    )
                    ->url(
                        fn(Capacitacion $record) =>
                        route(
                            'filament.admin.resources.capacitacions.edit',
                            [
                                $record,
                                'activeRelationManager' => 'sesiones', // slug del RM
                            ]
                        )
                    ),

                Actions\Action::make('ver_participantes')
                    ->label('Participantes')
                    ->icon('heroicon-o-users')
                    ->url(
                        fn(Capacitacion $record) =>
                        route(
                            'filament.admin.resources.capacitacions.edit',
                            [$record, 'activeRelationManager' => 'participantes']
                        )
                    ),
            ])
            ->bulkActions([

                Actions\DeleteBulkAction::make(),
            ]);
    }

    /* ---------------------------------------------------------------- Relaciones */
    public static function getRelations(): array
    {
        return [
            RelationManagers\SesionesRelationManager::class,
            RelationManagers\ParticipantesRelationManager::class,
        ];
    }

    /* ---------------------------------------------------------------- Pages */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCapacitacions::route('/'),
            'create' => Pages\CreateCapacitacion::route('/create'),
            'edit' => Pages\EditCapacitacion::route('/{record}/edit'),
            'curso' => Curso::route('/{record}/curso'),
        ];
    }
}
