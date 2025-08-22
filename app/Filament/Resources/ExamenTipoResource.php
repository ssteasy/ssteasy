<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamenTipoResource\Pages;
use App\Models\ExamenTipo;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ExamenTipoResource extends Resource
{
    protected static ?string $model = ExamenTipo::class;
    protected static ?string $navigationIcon = '';
    protected static ?string $navigationGroup = 'Gestión de Salud';
    // Opcional: fuerza el slug de la URL para que coincida
    protected static ?string $slug = 'examen-tipos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->label('Nombre de examen')
                ->required()
                ->maxLength(100),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Creado')->since(),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListExamenTipos::route('/'),
            'create'  => Pages\CreateExamenTipo::route('/create'),
            'edit'    => Pages\EditExamenTipo::route('/{record}/edit'),
        ];
    }
}
