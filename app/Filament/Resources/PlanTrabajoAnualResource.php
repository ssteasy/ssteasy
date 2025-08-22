<?php
// app/Filament/Resources/PlanTrabajoAnualResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanTrabajoAnualResource\Pages;
use App\Filament\Resources\PlanTrabajoAnualResource\RelationManagers\ActividadesRelationManager;
use App\Models\PlanTrabajoAnual;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form as FilamentForm;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;



class PlanTrabajoAnualResource extends Resource
{
    protected static ?string $model = PlanTrabajoAnual::class;
    protected static ?string $navigationIcon   = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Generalidades';
    protected static ?string $pluralLabel      = 'Planes Anuales';

    /* ──────────────────── Permisos ──────────────────── */
    public static function canViewAny(): bool  { return auth()->user()->hasRole(['admin','superadmin']); }
    public static function canCreate(): bool   { return auth()->user()->hasRole(['admin','superadmin']); }

    /* ─────────── Restringimos la vista a su empresa ─────────── */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('empresa_id', auth()->user()->empresa_id);
    }

    /* ──────────────────── Formulario ──────────────────── */
    public static function form(FilamentForm $form): FilamentForm
    {
        return $form->schema([
            /* Empresa siempre invisible en el payload */
            Hidden::make('empresa_id')
                ->default(fn () => auth()->user()->empresa_id),

            /* Campo Año: visible siempre, pero editable solo en “create” */
            TextInput::make('year')
                ->label('Año')
                ->numeric()
                ->required()
                ->unique(PlanTrabajoAnual::class, 'year', ignoreRecord: true)
                ->disabled(fn (?Model $record) => $record !== null) // bloqueado en edición
                ->dehydrated(),  // asegúrate de que siempre se envía al backend

                Textarea::make('roles_responsabilidades')
                ->label('Roles y Responsabilidades')
                ->rows(1)
                ->columnSpan('full')
                ->required(),

            Textarea::make('recursos')
                ->label('Recursos')
                ->rows(1)
                ->columnSpan('full')
                ->required(),

            Textarea::make('objetivo')
                ->label('Objetivo')
                ->rows(1)
                ->columnSpan('full')
                ->required(),

            Textarea::make('alcance')
                ->label('Alcance')
                ->rows(1)
                ->columnSpan('full')
                ->required(),

            /* Aviso solo en creación */
            Placeholder::make('info')
                ->content('Una vez creado el plan, no podrás cambiar el año.')
                ->visible(fn (?Model $record) => $record === null),
        ])->columns(2);
    }

    /* ──────────────────── Tabla ──────────────────── */
    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')->label('Año')->sortable(),
                Tables\Columns\TextColumn::make('actividades_count')->counts('actividades')->label('No. Actividades'),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('Descargar PDF')
                    ->url(fn (PlanTrabajoAnual $record) => route('export.plan.pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\ExportBulkAction::make('export_bulk'),
            ]);
    }

    /* ──────────────────── Relaciones ──────────────────── */
    public static function getRelations(): array
    {
        return [
            ActividadesRelationManager::class,
        ];
    }

    /* ──────────────────── Páginas ──────────────────── */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPlanTrabajoAnuals::route('/'),
            'create' => Pages\CreatePlanTrabajoAnual::route('/create'),
            'view'   => Pages\ViewPlanTrabajoAnual::route('/{record}'),
            'edit'   => Pages\EditPlanTrabajoAnual::route('/{record}/edit'),
        ];
    }
}
