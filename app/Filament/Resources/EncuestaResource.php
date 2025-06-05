<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EncuestaResource\Pages;
use App\Models\Encuesta;
use Filament\Resources\Resource;
use Filament\Facades\Filament;
use Filament\Forms\{Components as F, Form};
use Filament\Tables\{Actions, Columns as T, Table};
use Illuminate\Database\Eloquent\Builder;

class EncuestaResource extends Resource
{
    protected static ?string $model = Encuesta::class;

    protected static ?string $navigationLabel = 'Encuestas';
    protected static ?string $navigationGroup  = 'Capacitaciones';
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document';

    /* ---------------------------------------------------------- FORM */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                F\Select::make('empresa_id')
                    ->relationship('empresa', 'nombre')
                    ->default(fn () => Filament::auth()->user()->empresa_id)
                    ->disabled(fn () => ! Filament::auth()->user()->hasRole('superadmin'))
                    ->required(),

                F\Select::make('capacitacion_id')
                    ->relationship('capacitacion', 'nombre_capacitacion')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible(fn () => Filament::auth()->user()->hasAnyRole(['admin','superadmin'])),

                F\TextInput::make('codigo')->unique(ignoreRecord: true)->required(),
                F\TextInput::make('nombre')->required()->columnSpanFull(),

                F\Toggle::make('activa')->default(false),

                /* Preguntas repeatable */
                F\Repeater::make('preguntas')
                    ->relationship()
                    ->columnSpanFull()
                    ->orderColumn('id')
                    ->schema([
                        F\TextInput::make('enunciado')->required()->columnSpanFull(),
                        F\Select::make('tipo')
                            ->options([
                                'abierta'           => 'Abierta',
                                'opcion_multiple'   => 'Opción múltiple',
                                'escala'            => 'Escala (1-5)',
                            ])
                            ->reactive()
                            ->required(),
                        F\Textarea::make('opciones')
                            ->label('Opciones (una por línea)')
                            ->rows(3)
                            ->helperText('Sólo para “opción múltiple” o “escala”.')
                    ])
                    ->createItemButtonLabel('Agregar pregunta')
                    ->collapsed(),
            ])
            ->columns(2);
    }

    /* --------------------------------------------------------- TABLE */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                T\TextColumn::make('codigo')->searchable()->sortable(),
                T\TextColumn::make('nombre')->wrap(),
                T\TextColumn::make('capacitacion.nombre_capacitacion')
                    ->label('Capacitación')
                    ->limit(30)
                    ->sortable()
                    ->searchable(),
                T\BadgeColumn::make('empresa.nombre')
                    ->color('info')
                    ->visible(fn () => Filament::auth()->user()->hasRole('superadmin')),
                T\BooleanColumn::make('activa'),
            ])
            ->filters([

            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }

    /* --------------------------------------------------------- Pages */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEncuestas::route('/'),
            'create' => Pages\CreateEncuesta::route('/create'),
            'edit'   => Pages\EditEncuesta::route('/{record}/edit'),
        ];
    }
}
