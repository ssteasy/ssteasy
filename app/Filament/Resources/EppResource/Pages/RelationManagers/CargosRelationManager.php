<?php

namespace App\Filament\Resources\EppResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;              // ← importamos Fieldset
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

class CargosRelationManager extends RelationManager
{
    protected static string $relationship = 'eppCargos';
    protected static ?string $label = 'Cargos relacionados';
    protected static ?string $pluralLabel = 'Cargos relacionados';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cargo_id')
                    ->relationship('cargo', 'nombre')
                    ->label('Cargo')
                    ->required(),

                TextInput::make('cantidad')
                    ->label('Cantidad de ítems entregados')
                    ->numeric()
                    ->required(),

                Fieldset::make('Periodicidad del EPP')
                    ->columns(2)
                    ->schema([
                        TextInput::make('periodicidad_valor')
                            ->label('Cada')
                            ->numeric()
                            ->required(),
                        Select::make('periodicidad_unidad')
                            ->label('Unidad')
                            ->options([
                                'días' => 'Días',
                                'meses' => 'Meses',
                                'años' => 'Años',
                            ])
                            ->required(),
                    ]),



                Fieldset::make('Reposición')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reposicion_valor')
                            ->label('Plazo de reposición')
                            ->numeric()
                            ->required(),
                        Select::make('reposicion_unidad')
                            ->label('Unidad')
                            ->options([
                                'días' => 'Días',
                                'semanas' => 'Semanas',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cargo.nombre')
                    ->label('Cargo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('periodicidad_valor')
                    ->label('Periodicidad')
                    ->formatStateUsing(fn($state, $record) => "{$state} {$record->periodicidad_unidad}")
                    ->sortable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad entregada')
                    ->sortable(),

                TextColumn::make('reposicion_valor')
                    ->label('Plazo de reposición')
                    ->formatStateUsing(fn($state, $record) => "{$state} {$record->reposicion_unidad}")
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Relacionar cargo'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
