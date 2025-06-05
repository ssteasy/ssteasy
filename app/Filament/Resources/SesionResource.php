<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SesionResource\Pages;
use App\Filament\Resources\SesionResource\RelationManagers;
use App\Models\Sesion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SesionResource extends Resource
{
    protected static ?string $model = Sesion::class;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin']);
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('capacitacion_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('titulo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('contenido_html')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('video_url')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('orden')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('prerequisite_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\Toggle::make('requiere_aprobacion')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('capacitacion_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('titulo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('video_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orden')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prerequisite_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('requiere_aprobacion')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSesions::route('/'),
            'create' => Pages\CreateSesion::route('/create'),
            'edit' => Pages\EditSesion::route('/{record}/edit'),
        ];
    }
    //public static function getRelations(): array
    //{
    //    return [
     //       RelationManagers\PreguntasRelationManager::class,
     //   ];
    //}

}
