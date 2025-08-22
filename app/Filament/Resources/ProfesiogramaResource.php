<?php

// app/Filament/Resources/ProfesiogramaResource.php

namespace App\Filament\Resources;
use App\Models\ExamenTipo;
use App\Filament\Resources\ProfesiogramaResource\Pages;
use App\Models\Profesiograma;
use App\Models\ProfesiogramaExamenTipo;
use Filament\Infolists\Components\{
    Grid,
    Group,
    Section,
    TextEntry,
    RepeatableEntry,
    ImageEntry
};
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Repeater;
use App\Exports\ProfesiogramasExport;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MultiSelect;
use Closure;
use Filament\Forms\Get;
use Filament\Forms\Set;



class ProfesiogramaResource extends Resource
{
    protected static ?string $model = Profesiograma::class;
    protected static ?string $navigationIcon = '';
    protected static ?string $navigationGroup = 'Gestión de Salud';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('cargo_id')
                    ->relationship('cargo', 'nombre')
                    ->label('Cargo')
                    ->required(),

                /* ---------- Adjuntos ---------- */
                // ✅  FileUpload tradicional, SIN Section / ImageEntry:
                FileUpload::make('adjuntos')
                    ->label('Adjuntos (PDF/Imágenes)')
                    ->multiple()
                    ->directory('profesiogramas')
                    ->nullable(),

                Textarea::make('tareas')
                    ->label('Tareas asociadas')
                    ->required(),

                Textarea::make('funciones')
                    ->label('Funciones')
                    ->required(),

                MultiSelect::make('vacunas')
                    ->relationship('vacunas', 'nombre')
                    ->label('Vacunas requeridas')
                    ->helperText('Selecciona todas las vacunas aplicables'),

                MultiSelect::make('epps')
                    ->relationship('epps', 'nombre')
                    ->label('EPP requerido')
                    ->helperText('Selecciona los elementos de protección'),

                Repeater::make('profesiogramaExamenTipos')
                    ->label('Exámenes médicos requeridos')
                    ->relationship('profesiogramaExamenTipos')
                    ->collapsible()    // permite colapsar/expandir cada ítem :contentReference[oaicite:0]{index=0}
                    ->collapsed()      // colapsado por defecto :contentReference[oaicite:1]{index=1}
                    ->itemLabel(
                        fn(array $state): ?string =>
                            // muestra examen + tipificación en el header
                        ($ex = ExamenTipo::find($state['examen_tipo_id']))
                        ? "{$ex->nombre} ({$state['tipificacion']})"
                        : null
                    )                 // etiqueta compacta arriba de cada ítem :contentReference[oaicite:2]{index=2}
                    ->schema([
                        Select::make('examen_tipo_id')
                            ->relationship('examenTipo', 'nombre')
                            ->label('Tipo de examen')
                            ->required(),

                        Select::make('tipificacion')
                            ->label('Tipificación')
                            ->options([
                                'Ingreso' => 'Ingreso',
                                'Egreso' => 'Egreso',
                                'Periódico' => 'Periódico',
                                'Post Incapacidad' => 'Post Incapacidad',
                            ])
                            ->default('Periódico')
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state !== 'Periódico') {
                                    $set('periodicidad_valor', null);
                                    $set('periodicidad_unidad', null);
                                }
                            }),

                        TextInput::make('periodicidad_valor')
                            ->label('Cada')
                            ->numeric()
                            ->minValue(1)
                            ->reactive()
                            ->required(fn(Get $get) => $get('tipificacion') === 'Periódico')
                            ->hidden(fn(Get $get) => $get('tipificacion') !== 'Periódico'),

                        Select::make('periodicidad_unidad')
                            ->label('Unidad')
                            ->options([
                                'días' => 'Días',
                                'meses' => 'Meses',
                                'años' => 'Años',
                            ])
                            ->reactive()
                            ->required(fn(Get $get) => $get('tipificacion') === 'Periódico')
                            ->hidden(fn(Get $get) => $get('tipificacion') !== 'Periódico'),
                    ])
                    ->columns(4)
                    ->minItems(1),
            ]);
    }




    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('cargo.nombre')->label('Cargo'),
                TextColumn::make('tareas')->limit(50),
                TextColumn::make('funciones')->limit(50),
                TextColumn::make('created_at')->date(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('')
                    ->action(fn(Profesiograma $rec) => Pages\GeneratePdf::run($rec)),
            ])
            ->bulkActions([
                BulkAction::make('export')
                    ->label('Exportar Excel')
                    ->action(fn() => (new ProfesiogramasExport())->download('profesiogramas.xlsx')),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfesiogramas::route('/'),
            'create' => Pages\CreateProfesiograma::route('/create'),
            'view' => Pages\ViewProfesiograma::route('/{record}'),
            'edit' => Pages\EditProfesiograma::route('/{record}/edit'),
        ];
    }
}
