<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EncuestaResource\RelationManagers\UsuariosRelationManager;
use App\Filament\Resources\EncuestaResource\Pages;
use App\Models\Encuesta;
use Filament\Resources\Resource;
use Filament\Facades\Filament;
use Filament\Forms\{Components as F, Form};
use Filament\Tables\{Actions, Columns as T, Table};
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;


class EncuestaResource extends Resource
{
    protected static ?string $model = Encuesta::class;

    protected static ?string $navigationLabel = 'Encuestas';
    protected static ?string $navigationGroup = 'Gestión del conocimiento';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1) Select visible sólo para superadmin
                Select::make('empresa_id')
                    ->relationship('empresa', 'nombre')
                    ->default(fn() => Filament::auth()->user()->empresa_id)
                    ->required()
                    ->visible(fn() => Filament::auth()->user()->hasRole('superadmin')),

                // 2) Hidden para todos los demás
                Hidden::make('empresa_id')
                    ->default(fn() => Filament::auth()->user()->empresa_id)
                    ->visible(fn() => !Filament::auth()->user()->hasRole('superadmin')),

                // Resto de campos
                Select::make('capacitacion_id')
                    ->relationship('capacitacion', 'nombre_capacitacion')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),

                TextInput::make('codigo')
                    ->unique(ignoreRecord: true)
                    ->required(),

                TextInput::make('nombre')
                    ->required()
                    ->columnSpanFull(),

                Toggle::make('activa')
                    ->default(false),

                Repeater::make('preguntas')
                    ->label('Preguntas')
                    ->schema([
                        TextInput::make('enunciado')->required(),
                        Select::make('tipo')
                            ->options([
                                'abierta' => 'Abierta',
                                'opcion_multiple' => 'Opción múltiple',
                                'escala' => 'Escala (1–5)',
                            ])
                            ->reactive()
                            ->required(),
                        Textarea::make('opciones')
                            ->label('Opciones (una por línea)')
                            ->rows(3)
                            ->helperText('Sólo para “opción múltiple” o “escala”.'),
                    ])
                    ->createItemButtonLabel('Agregar pregunta')
                    ->columnSpanFull()
                    ->collapsed(),
            ])
            ->columns(2);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                T\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                T\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->wrap(),
                T\TextColumn::make('capacitacion.nombre_capacitacion')
                    ->label('Capacitación')
                    ->limit(30)
                    ->sortable()
                    ->searchable(),
                // Conteo de preguntas en el JSON
                T\TextColumn::make('preguntas')
                    ->label('N° Preguntas')
                    ->getStateUsing(fn(Encuesta $record) => count($record->preguntas ?? [])),
                // Solo visible para superadmin
                T\BadgeColumn::make('empresa.nombre')
                    ->label('Empresa')
                    ->color('info')
                    ->visible(fn() => Filament::auth()->user()->hasRole('superadmin')),
                T\BooleanColumn::make('activa')
                    ->label('Activa'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Sólo para colaboradores
                Action::make('responder')
                    ->label('Responder')
                    ->icon('heroicon-o-pencil')
                    ->visible(
                        fn(Encuesta $record) => Filament::auth()->user()->hasRole('colaborador')
                        && $record->usuarios->contains(Filament::auth()->id())
                        && !$record->usuarios->firstWhere('id', Filament::auth()->id())->pivot->respondida
                    )
                    ->form(function (Encuesta $record): array {
                        // Genera dinámicamente los campos según el JSON de preguntas
                        $schema = [];
                        foreach ($record->preguntas as $index => $pregunta) {
                            $key = "q_{$index}";
                            switch ($pregunta['tipo']) {
                                case 'abierta':
                                    $schema[] = F\TextInput::make($key)
                                        ->label($pregunta['enunciado'])
                                        ->required();
                                    break;
                                case 'opcion_multiple':
                                    $options = explode("\n", $pregunta['opciones']);
                                    $schema[] = F\Select::make($key)
                                        ->label($pregunta['enunciado'])
                                        ->options(array_combine($options, $options))
                                        ->required();
                                    break;
                                case 'escala':
                                    $options = explode("\n", $pregunta['opciones']);
                                    $schema[] = F\Select::make($key)
                                        ->label($pregunta['enunciado'])
                                        ->options(array_combine($options, $options))
                                        ->required();
                                    break;
                            }
                        }
                        return $schema;
                    })
                    ->action(function (array $data, Encuesta $record) {
                        $user = Filament::auth()->user();
                        // Guarda el array de respuestas en JSON
                        $user->encuestas()->updateExistingPivot($record->id, [
                            'respuestas' => json_encode($data),
                            'respondida' => true,
                            'respondido_at' => now(),
                        ]);
                        Notification::make()
                            ->success()
                            ->title('¡Encuesta respondida!')
                            ->body('Gracias por completar la encuesta.')
                            ->send();
                    })
                    ->modalWidth('xl'),
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsuariosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEncuestas::route('/'),
            'create' => Pages\CreateEncuesta::route('/create'),
            'edit' => Pages\EditEncuesta::route('/{record}/edit'),
        ];
    }
}
