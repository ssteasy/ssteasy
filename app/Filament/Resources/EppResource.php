<?php
// app/Filament/Resources/EppResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\EppResource\Pages;
use App\Filament\Resources\EppResource\RelationManagers\CargosRelationManager;
use App\Models\Epp;
use Filament\Forms\Components\{TextInput, FileUpload};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Malzariey\FilamentLexicalEditor\FilamentLexicalEditor;
use Malzariey\FilamentLexicalEditor\ToolbarItem; // ← import de constantes :contentReference[oaicite:0]{index=0}

class EppResource extends Resource
{
    protected static ?string $model = Epp::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Administración de EPP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->maxLength(100),

                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(150),

                FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
                    ->directory('epps')
                    ->nullable(),

                TextInput::make('codigo_barras')
                    ->label('Código de barras')
                    ->nullable()
                    ->maxLength(255),
                FilamentLexicalEditor::make('observaciones')
                    ->label('Observaciones')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')->label('Código')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nombre')->label('Nombre')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizado')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [CargosRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEpps::route('/'),
            'create' => Pages\CreateEpp::route('/create'),
            'edit' => Pages\EditEpp::route('/{record}/edit'),
        ];
    }
}
