<?php

namespace App\Filament\Resources\GestionCambioResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Filament\Resources\RelationManagers\RelationManager;

class ActividadesRelationManager extends RelationManager
{
    protected static string $relationship = 'actividades';
    // Esto le dice a Filament qué atributo usar como título de cada registro
    protected static ?string $recordTitleAttribute = 'actividad';
    protected static ?string $label                  = 'Planeación y Seguimiento';

    public function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('realizado_por')
                ->default(fn() => auth()->id())
                ->dehydrated()     // asegura que se incluya en el array de datos
                ->hidden(),        // oculta el campo del UI
            Forms\Components\TextInput::make('actividad')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('responsable_id')
                ->label('Responsable')
                ->relationship('responsable', 'primer_nombre')  // columna real
                ->searchable()
                ->required(),
            Forms\Components\Select::make('informar_a_id')
                ->label('Comunicar cambio a')
                ->relationship('informarA', 'primer_nombre')
                ->searchable(),

            Forms\Components\DatePicker::make('fecha_ejecucion')
                ->label('Fecha de ejecución')
                ->required(),

            Forms\Components\DatePicker::make('fecha_seguimiento')
                ->label('Fecha de seguimiento'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('actividad')->wrap(),
                Tables\Columns\TextColumn::make('responsable.name')->label('Responsable'),
                Tables\Columns\TextColumn::make('informarA.name')->label('Comunicar a'),
                Tables\Columns\TextColumn::make('fecha_ejecucion')->date()->label('Ejecución'),
                Tables\Columns\TextColumn::make('fecha_seguimiento')->date()->label('Seguimiento'),
            ])
            // Aquí habilitamos el botón “Agregar actividad”
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar actividad'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
