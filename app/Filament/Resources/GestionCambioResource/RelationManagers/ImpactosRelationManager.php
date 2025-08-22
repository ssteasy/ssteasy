<?php

namespace App\Filament\Resources\GestionCambioResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class ImpactosRelationManager extends RelationManager
{
    protected static string $relationship = 'impactos';
    protected static ?string $recordTitleAttribute = 'peligro_riesgo';
    protected static ?string $label                  = 'Análisis de Impacto';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('peligro_riesgo')
                ->label('Peligro / Riesgo')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('requisitos_legales')
                ->label('Requisitos legales aplicables')
                ->maxLength(255),
            Forms\Components\TextInput::make('sistema_gestion')
                ->label('Sistema de gestión')
                ->maxLength(255),
            Forms\Components\TextInput::make('procedimiento')
                ->label('Procedimiento / Instrucción de trabajo')
                ->maxLength(255),
            Forms\Components\TextInput::make('otros')
                ->label('Otros')
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peligro_riesgo')
                    ->label('Peligro / Riesgo')
                    ->wrap(),
                Tables\Columns\TextColumn::make('requisitos_legales')
                    ->label('Requisitos legales')
                    ->wrap(),
                Tables\Columns\TextColumn::make('sistema_gestion')
                    ->label('Sistema de gestión')
                    ->wrap(),
                Tables\Columns\TextColumn::make('procedimiento')
                    ->label('Procedimiento')
                    ->wrap(),
                Tables\Columns\TextColumn::make('otros')
                    ->label('Otros')
                    ->wrap(),
            ])
            // esto te da el botón de “Agregar” arriba de la tabla
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar impacto'),
            ])
            // acciones sobre cada fila
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // acciones masivas
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
