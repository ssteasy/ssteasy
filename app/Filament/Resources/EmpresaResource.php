<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Models\Empresa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->label('Nombre comercial')
                    ->required()
                    ->maxLength(255),
    
                TextInput::make('nit')
                    ->label('NIT')
                    ->required()
                    ->maxLength(20),
    
                TextInput::make('razon_social')
                    ->label('Razón social')
                    ->required()
                    ->maxLength(255),
    
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
    
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->required()
                    ->maxLength(20),
    
                TextInput::make('direccion')
                    ->label('Dirección')
                    ->required()
                    ->maxLength(255),
    
                TextInput::make('ciudad')
                    ->required()
                    ->maxLength(100),
    
                TextInput::make('website')
                    ->url()
                    ->maxLength(255),
    
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->imageEditor()
                    ->directory('logos') // ← subcarpeta dentro de public
                    ->disk('public')
                    ->visibility('public')
                    ->imagePreviewHeight('100'),
    
                Toggle::make('activo')
                    ->label('Empresa activa')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->height(40)
                    ->width(40),
                TextColumn::make('nombre')->searchable()->sortable(),
                TextColumn::make('nit')->sortable(),
                TextColumn::make('ciudad')->sortable(),
                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
