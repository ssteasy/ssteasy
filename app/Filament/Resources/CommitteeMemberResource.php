<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommitteeMemberResource\Pages;
use App\Filament\Resources\CommitteeMemberResource\RelationManagers;
use App\Models\CommitteeMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommitteeMemberResource extends Resource {
    protected static ?string $model = CommitteeMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestión de Comités';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('committee_id')
                    ->label('Comité')
                    ->relationship(
                        name: 'committee',
                        modifyQueryUsing: fn (Builder $query) => 
                            auth()->user()->hasRole('superadmin') 
                                ? $query 
                                : $query->where('empresa_id', auth()->user()->empresa_id)
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nombre} - {$record->empresa->nombre}")
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->options(
                        fn () => \App\Models\User::query()
                            ->where('empresa_id', auth()->user()->empresa_id)
                            ->with('cargo')
                            ->get()
                            ->mapWithKeys(fn ($user) => [
                                $user->id => "{$user->primer_nombre} {$user->primer_apellido} - {$user->cargo->nombre}"
                            ])
                    )
                    ->searchable(['primer_nombre', 'primer_apellido', 'numero_documento'])
                    ->required(),

                Forms\Components\Select::make('tipo_representante')
                    ->options([
                        'representante_colaboradores' => 'Representante de Colaboradores',
                        'representante_trabajador' => 'Representante del Trabajador'
                    ])
                    ->required()
                    ->native(false),

                Forms\Components\Textarea::make('rol_en_comite')
                    ->label('Rol en el Comité')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('foto')
                    ->label('Foto del Miembro')
                    ->directory('public/comites/miembros')
                    ->image()
                    ->imageEditor()
                    ->openable()
                    ->downloadable()
                    ->visibility('public')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('committee.nombre')
                    ->label('Comité')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.primer_nombre')
                    ->label('Nombre')
                    ->formatStateUsing(fn ($state, $record) => 
                        "{$record->user->primer_nombre} {$record->user->primer_apellido}")
                    ->searchable(),

                Tables\Columns\TextColumn::make('tipo_representante')
                    ->label('Tipo de Representante')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'representante_colaboradores' => 'success',
                        'representante_trabajador' => 'warning',
                    }),

                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('local')
                    ->visibility('public'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('committee')
                    ->relationship('committee', 'nombre')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('tipo_representante')
                    ->options([
                        'representante_colaboradores' => 'Colaboradores',
                        'representante_trabajador' => 'Trabajador'
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => 
                        auth()->user()->hasRole('admin') && 
                        $record->committee->empresa_id === auth()->user()->empresa_id
                    ),
                    
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => 
                        auth()->user()->hasRole('admin') && 
                        $record->committee->empresa_id === auth()->user()->empresa_id
                    ),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(!auth()->user()->hasRole('superadmin'), function ($query) {
                $query->whereHas('committee', function ($q) {
                    $q->where('empresa_id', auth()->user()->empresa_id);
                });
            });
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommitteeMembers::route('/'),
            'create' => Pages\CreateCommitteeMember::route('/create'),
            'edit' => Pages\EditCommitteeMember::route('/{record}/edit'),
        ];
    }
}