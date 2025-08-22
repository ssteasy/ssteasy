<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamenMedicoResource\Pages;
use App\Models\{Cargo, ExamenMedico, ProfesiogramaExamenTipo, Sede, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Forms;
use Filament\Forms\Components\{
    DatePicker,
    FileUpload,
    Select,
    Textarea,
    TextInput,
    Toggle
};
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ExamenMedicoResource extends Resource
{
    protected static ?string $model           = ExamenMedico::class;
    protected static ?string $navigationGroup = 'Gestión de Salud';
    protected static ?string $navigationIcon  = '';

    /** --------------------------------------------------------------------
     *  Query restringido a los colaboradores que inician sesión
     *  ------------------------------------------------------------------*/
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()->hasRole('colaborador'),
                fn (Builder $q) => $q->where('user_id', auth()->id())
            );
    }

    /** --------------------------------------------------------------------
     *  FORMULARIO
     *  ------------------------------------------------------------------*/
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            /* -----------   SWITCH MASIVO / INDIVIDUAL   ----------- */
            Toggle::make('masivo')
                ->label('Asignación masiva')
                ->helperText('Actívalo para asignar el mismo examen a muchos usuarios')
                ->reactive()
                ->default(false)
                ->dehydrated(false)   // no guardar en BD
                ->columnSpanFull(),

            /* -----------   FILTROS PARA MASIVO   ----------- */
            Select::make('cargo_ids')
                ->label('Cargo')
                ->multiple()
                ->options(fn () => Cargo::pluck('nombre', 'id'))
                ->visible(fn (Get $get) => $get('masivo'))
                ->required(fn (Get $get) => $get('masivo'))
                ->columnSpan(2),

            Select::make('sede_ids')
                ->label('Sede')
                ->multiple()
                ->options(fn () => Sede::pluck('nombre', 'id'))
                ->visible(fn (Get $get) => $get('masivo'))
                ->required(fn (Get $get) => $get('masivo'))
                ->columnSpan(2),

            Select::make('sexo')
                ->label('Sexo')
                ->options(['M' => 'Hombre', 'F' => 'Mujer'])
                ->visible(fn (Get $get) => $get('masivo'))
                ->columnSpan(1),

            /* -----------   COLABORADOR INDIVIDUAL   ----------- */
            Select::make('user_id')
                ->label('Colaborador')
                ->searchable()
                ->getSearchResultsUsing(function (string $search): array {
                    return User::query()
                        ->when(
                            auth()->user()->hasRole('admin'),
                            fn ($q) => $q->where('empresa_id', auth()->user()->empresa_id),
                            fn ($q) => $q->where('id', auth()->id()),
                        )
                        ->where(function ($q) use ($search) {
                            $like = "%{$search}%";
                            $q->where('primer_nombre', 'like', $like)
                              ->orWhere('segundo_nombre', 'like', $like)
                              ->orWhere('primer_apellido', 'like', $like)
                              ->orWhere('segundo_apellido', 'like', $like);
                        })
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(fn (User $u) => [
                            $u->id => "{$u->primer_nombre} {$u->segundo_nombre} {$u->primer_apellido} {$u->segundo_apellido}",
                        ])
                        ->toArray();
                })
                ->getOptionLabelUsing(fn ($value): ?string => optional(User::find($value))
                    ?->primer_nombre . ' ' .
                    optional(User::find($value))?->segundo_nombre . ' ' .
                    optional(User::find($value))?->primer_apellido . ' ' .
                    optional(User::find($value))?->segundo_apellido)
                ->required(fn (Get $get) => ! $get('masivo'))
                ->visible(fn (Get $get) => ! $get('masivo'))
                ->columnSpan(4),

            /* -----------   DATOS DEL EXAMEN   ----------- */
            Select::make('profesiograma_examen_tipo_id')
                ->label('Tipo examen (Profesiograma)')
                ->options(fn () => ProfesiogramaExamenTipo::with('examenTipo')
                    ->whereHas('profesiograma')
                    ->get()
                    ->mapWithKeys(fn ($item) => [
                        $item->id => "{$item->examenTipo->nombre} ({$item->tipificacion})",
                    ]))
                ->searchable()
                ->required(),

            DatePicker::make('fecha_examen')
                ->label('Fecha del examen')
                ->required(),

            Select::make('tipificacion')
                ->label('Tipificación')
                ->options([
                    'Ingreso'          => 'Ingreso',
                    'Egreso'           => 'Egreso',
                    'Periódico'        => 'Periódico',
                    'Post Incapacidad' => 'Post Incapacidad',
                ])
                ->required(),

            DatePicker::make('fecha_siguiente')
                ->label('Próximo examen')
                ->helperText('Se calculará automáticamente si es Periódico')
                ->disabled(),

            Select::make('concepto_medico')
                ->label('Concepto médico')
                ->options([
                    'Apto'                    => 'Apto',
                    'Apto con restricciones'  => 'Apto con restricciones',
                    'No apto'                 => 'No apto',
                ])
                ->required(),

            Textarea::make('recomendaciones')
                ->label('Recomendaciones')
                ->nullable(),

            FileUpload::make('adjuntos')
                ->label('Adjuntos Médicos')
                ->multiple()
                ->directory('examenes_medicos')
                ->nullable(),
                ]);
    }

    /** --------------------------------------------------------------------
     *  TABLA
     *  ------------------------------------------------------------------*/
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('usuario.name')->label('Colaborador'),
                TextColumn::make('profesiogramaExamenTipo.examenTipo.nombre')->label('Examen'),
                TextColumn::make('fecha_examen')->label('Fecha')->date(),
                TextColumn::make('tipificacion')->label('Tipificación'),
                TextColumn::make('concepto_medico')->label('Concepto'),
                TextColumn::make('fecha_siguiente')->label('Próximo')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /** --------------------------------------------------------------------
     *  RUTAS
     *  ------------------------------------------------------------------*/
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListExamenMedicos::route('/'),
            'create' => Pages\CreateExamenMedico::route('/create'),
            'edit'   => Pages\EditExamenMedico::route('/{record}/edit'),
        ];
    }
}
