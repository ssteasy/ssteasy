<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RolResource\Pages;
use App\Models\Rol;
use App\Models\Cargo;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;


class RolResource extends Resource
{
    protected static ?string $model = Rol::class;
    protected static ?string $navigationGroup = 'Gestión Empresarial';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin']);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                ! auth()->user()->hasRole('superadmin'),
                fn (Builder $q) => $q->where('empresa_id', auth()->user()->empresa_id)
            );
    }



   public static function form(Forms\Form $form): Forms\Form
  {
    return $form
        ->schema([
            // ————— Empresa (solo superadmin) —————
            Select::make('empresa_id')
                ->label('Empresa')
                ->relationship('empresa', 'nombre')
                ->searchable()
                ->preload()
                ->required()
                ->visible(fn (): bool => auth()->user()->hasRole('superadmin'))
                ->reactive(),

            // ————— Cargo (filtrado por empresa) —————
            Select::make('cargo_id')
                ->label('Cargo')
                ->options(fn (callable $get) =>
                    \App\Models\Cargo::query()
                        ->when(
                            auth()->user()->hasRole('superadmin'),
                            fn (Builder $q) => $q->where('empresa_id', $get('empresa_id'))
                        )
                        ->when(
                            ! auth()->user()->hasRole('superadmin'),
                            fn (Builder $q) => $q->where('empresa_id', auth()->user()->empresa_id)
                        )
                        ->pluck('nombre', 'id')
                )
                ->searchable()
                ->preload()
                ->required()
                ->reactive(),

            // ← Aquí va tu campo PARA EL NOMBRE DEL ROL
            TextInput::make('nombre')
                ->label('Nombre del rol')
                ->required()
                ->maxLength(100),

            // ————— Toggle Activo —————
            Toggle::make('activo')
                ->label('Activo')
                ->default(true),
        ]);
}

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('nombre')->searchable()->sortable(),
                TextColumn::make('cargo.nombre')->label('Cargo'),
                IconColumn::make('activo')->boolean()->label('Activo'),
                TextColumn::make('created_at')->dateTime()->label('Creado'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRols::route('/'),
            'create' => Pages\CreateRol::route('/create'),
            'edit'   => Pages\EditRol::route('/{record}/edit'),
        ];
    }
}
