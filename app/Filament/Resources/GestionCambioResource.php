<?php
namespace App\Filament\Resources;

use App\Models\GestionCambio;
use App\Enums\CambioEstado;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GestionCambioResource\Pages;
use App\Filament\Resources\GestionCambioResource\RelationManagers\ImpactosRelationManager;
use App\Filament\Resources\GestionCambioResource\RelationManagers\ActividadesRelationManager;


class GestionCambioResource extends Resource
{
    protected static ?string $model = GestionCambio::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    
    protected static ?string $navigationGroup = 'Documentación de procesos';

    /* -------- Restricción por empresa -------- */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return auth()->user()->hasRole('superadmin')
            ? $query
            : $query->where('empresa_id', auth()->user()->empresa_id);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\DatePicker::make('fecha')->required(),
                // Campo oculto para TODO USUARIO, con default de la empresa del usuario
                Forms\Components\Hidden::make('empresa_id')
                    ->default(fn() => auth()->user()->empresa_id)
                    ->required()
                    ->visible(fn() => !auth()->user()->hasRole('superadmin')),

                // Selector SOLO para superadmins
                Forms\Components\Select::make('empresa_id')
                    ->relationship('empresa', 'nombre')
                    ->required()
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
            ]),
            Forms\Components\TextInput::make('descripcion_cambio')
                ->columnSpanFull()->required(),
            Forms\Components\Textarea::make('analisis_riesgo')->rows(2),
            Forms\Components\Textarea::make('requisitos_legales')->rows(2),
            Forms\Components\Textarea::make('requerimientos_sst')->rows(2),
            Forms\Components\RichEditor::make('analisis_impacto_sst')
                ->columnSpanFull(),

            Forms\Components\Select::make('estado')
                ->options(CambioEstado::labels())
                ->default(CambioEstado::PLANIFICADO)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Código')->sortable(),
                Tables\Columns\TextColumn::make('fecha')->date()->sortable(),
                Tables\Columns\TextColumn::make('descripcion_cambio')->limit(40)->wrap(),
                Tables\Columns\BadgeColumn::make('estado')
                    ->formatStateUsing(
                        /** @param  CambioEstado|string  $state */
                        fn($state): string => CambioEstado::labels()[
                            $state instanceof CambioEstado
                            ? $state->value
                            : $state
                        ] ?? (string) $state
                    )
                    ->colors([
                        'secondary' => CambioEstado::PLANIFICADO->value,
                        'warning' => CambioEstado::EN_EJECUCION->value,
                        'success' => CambioEstado::EJECUTADO->value,
                    ]),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(CambioEstado::labels()),
                Tables\Filters\Filter::make('fecha')
                    ->form([
                        Forms\Components\DatePicker::make('desde'),
                        Forms\Components\DatePicker::make('hasta')
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn($q, $value) => $q->whereDate('fecha', '>=', $value))
                            ->when($data['hasta'], fn($q, $value) => $q->whereDate('fecha', '<=', $value));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn(GestionCambio $record) =>
                        route('gestion-cambio.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            ImpactosRelationManager::class,
            ActividadesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGestionCambios::route('/'),
            'create' => Pages\CreateGestionCambio::route('/create'),
            'edit' => Pages\EditGestionCambio::route('/{record}/edit'),
        ];
    }
}
