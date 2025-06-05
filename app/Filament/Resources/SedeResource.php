<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SedeResource\Pages;
use App\Models\Empresa;
use App\Models\Sede;
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
use Filament\Forms\Components\Hidden;

class SedeResource extends Resource
{
    public static function getNavigationGroup(): ?string
    {
        return 'Mi Empresa';
    }

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';
    protected static ?string $model = Sede::class;
    
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
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('empresa_id')
                        ->default(fn () => auth()->user()->empresa_id)
                        ->dehydrated()      // asegúrate de que se envíe en el payload
                        ->required(),
                // Empresa solo para superadmin
                Select::make('empresa_id')
                    ->label('Empresa')
                    ->options(Empresa::pluck('nombre', 'id'))
                    ->searchable()
                    ->required()
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),

                TextInput::make('codigo')
                    ->label('Código de sede')
                    ->required(),

                TextInput::make('nombre')
                    ->label('Nombre de la sede')
                    ->required(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                TextColumn::make('codigo')->sortable()->searchable(),
                TextColumn::make('nombre')->sortable()->searchable(),
                TextColumn::make('empresa.nombre')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
                IconColumn::make('activo')->boolean(),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('empresa_id', auth()->user()->empresa_id);
        }
        return $query;
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSedes::route('/'),
            'create' => Pages\CreateSede::route('/create'),
            'edit'   => Pages\EditSede::route('/{record}/edit'),
        ];
    }
}
