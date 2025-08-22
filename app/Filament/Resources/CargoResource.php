<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CargoResource\Pages;
use App\Models\Empresa;
use App\Models\Cargo;
use Filament\Forms\Form;
use Filament\Forms\Components\{Select, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Tables; // import Filament\Tables for filters/columns
use Filament\Tables\Table as FilamentTable;
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\{EditAction, DeleteAction, DeleteBulkAction};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CargoResource extends Resource
{

    protected static ?string $navigationGroup = 'Gestión Empresarial';
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $model = Cargo::class;

    // Definir página principal (index)
    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListCargos::route('/'),
            'create'  => Pages\CreateCargo::route('/create'),
            'edit'    => Pages\EditCargo::route('/{record}/edit'),
        ];
    }

    // Autorización
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        if ($user->hasRole('superadmin')) {
            return true;
        }
        return $user->hasRole('admin') && $record->empresa_id === $user->empresa_id;
    }

    public static function canDelete(Model $record): bool
    {
        return static::canEdit($record);
    }

    // Query con filtro multiempresa
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('empresa_id', auth()->user()->empresa_id);
        }
        return $query;
    }

    // Formulario
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Empresa solo para superadmin
                Select::make('empresa_id')
                    ->label('Empresa')
                    ->options(Empresa::pluck('nombre', 'id'))
                    ->searchable()
                    ->required()
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),

                TextInput::make('codigo')
                    ->label('Código del cargo')
                    ->required(),

                TextInput::make('nombre')
                    ->label('Nombre del cargo')
                    ->required(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    // Tabla
    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nombre')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('empresa.nombre')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),

                IconColumn::make('activo')
                    ->boolean()
                    ->label('Activo'),
            ])
            ->filters([
                SelectFilter::make('empresa_id')
                    ->label('Empresa')
                    ->options(Empresa::pluck('nombre', 'id'))
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    // Asegurar asignación de empresa para admins
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }
        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (! auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }
        return $data;
    }
}
