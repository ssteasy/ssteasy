<?php

namespace App\Filament\Resources\PlanTrabajoAnualResource\RelationManagers;

use App\Models\PlanActividad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActividadesRelationManager extends RelationManager
{
    protected static string $relationship = 'actividades';
    protected static ?string $recordTitleAttribute = 'actividad';
    protected static ?string $label = 'Actividades';

    public function form(Form $form): Form
    {
        $months = [
            'ene' => 'Enero',
            'feb' => 'Febrero',
            'mar' => 'Marzo',
            'abr' => 'Abril',
            'may' => 'Mayo',
            'jun' => 'Junio',
            'jul' => 'Julio',
            'ago' => 'Agosto',
            'sep' => 'Septiembre',
            'oct' => 'Octubre',
            'nov' => 'Noviembre',
            'dic' => 'Diciembre',
        ];

        $monthFields = collect($months)->map(fn($label, $key) =>
            Select::make("mes_{$key}")
                ->label($label)
                ->options([
                    'planear'   => 'Planear',
                    'pospuesta' => 'Pospuesta',
                    'ejecutada' => 'Ejecutada',
                    'no_aplica' => 'No aplica',
                ])
                ->default('no_aplica')
                ->reactive()
                ->required()
                ->extraInputAttributes(fn (callable $get) => [
                    'style' => match ($get("mes_{$key}")) {
                        // Celeste claro fondo, azul oscuro texto y borde
                        'planear'   => 'background-color: #D0F0FD; color: #003366; border: 1px solid #003366;',
                        // Amarillo claro fondo, amarillo oscuro texto y borde
                        'pospuesta' => 'background-color: #FFF9C4; color: #8E7F00; border: 1px solid #8E7F00;',
                        // Gris muy claro fondo, gris oscuro texto y borde
                        'no_aplica' => 'background-color: #F5F5F5; color: #4F4F4F; border: 1px solid #4F4F4F;',
                        // Verde claro fondo, verde oscuro texto y borde
                        'ejecutada' => 'background-color: #E8F5E9; color: #1B5E20; border: 1px solid #1B5E20;',
                        default     => '',
                    },
                ])
        )->toArray();

        return $form
            ->schema([
                Textarea::make('actividad')
                    ->label('Actividad')
                    ->required(),

                TextInput::make('responsable')
                    ->label('Responsable')
                    ->required(),

                Textarea::make('alcance')
                    ->label('Alcance')
                    ->required(),

                Textarea::make('criterio')
                    ->label('Criterio')
                    ->required(),

                Textarea::make('observacion')
                    ->label('Observación'),

                TextInput::make('frecuencia')
                    ->label('Frecuencia')
                    ->required(),

                Section::make('Calendario Anual')
                    ->description('Selecciona el estado de cada mes')
                    ->schema($monthFields)
                    ->columns(4)
                    ->columnSpan('full')
                    ->extraAttributes([
                        'class' => 'space-y-4',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('actividad')->label('Actividad')->limit(30),
                Tables\Columns\TextColumn::make('responsable')->label('Responsable'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
