<?php

namespace App\Filament\Resources\CapacitacionResource\RelationManagers;

use App\Models\Sesion;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\{
    Hidden,
    Select,
    TextInput,
    FileUpload
};
use Malzariey\FilamentLexicalEditor\FilamentLexicalEditor;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\{
    ImageColumn,
    TextColumn
};
use Filament\Tables\Actions;
use Illuminate\Database\Eloquent\Model;

class SesionesRelationManager extends RelationManager
{
    protected static string $relationship = 'sesiones';
    protected static ?string $recordTitleAttribute = 'titulo';
    protected static ?string $pluralModelLabel     = 'Clases / Sesiones';
    protected static ?string $title                = 'Sesiones';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Hidden::make('capacitacion_id')
                ->default(fn () => $this->getOwnerRecord()->id)
                ->dehydrated(),

            Hidden::make('created_by')
                ->default(fn () => auth()->id())
                ->dehydrated(),

            TextInput::make('titulo')
                ->label('Título de la clase')
                ->required()
                ->columnSpanFull(),

            FileUpload::make('miniatura')
                ->label('Miniatura (opcional)')
                ->image()
                ->directory('sesiones/miniaturas')
                ->maxSize(1024)
                ->nullable()
                ->columnSpanFull(),

            FilamentLexicalEditor::make('contenido_html')
                ->label('Contenido')
                ->required()
                ->columnSpanFull(),

            TextInput::make('video_url')
                ->label('URL de video (opcional)')
                ->url()
                ->nullable()
                ->columnSpanFull(),

            Select::make('prerequisite_id')
                ->label('Debe completar antes')
                ->helperText('Dejar vacío si es la primera clase')
                ->options(fn () =>
                    $this->getOwnerRecord()
                        ->sesiones()
                        ->pluck('titulo', 'id')
                )
                ->searchable()
                ->nullable()
                ->columnSpan(2),

            Forms\Components\Repeater::make('preguntas')
                ->label('Preguntas de la clase')
                ->columns(1)
                ->schema([
                    Select::make('tipo')
                        ->label('Tipo')
                        ->options([
                            'vf'      => 'Verdadero / Falso',
                            'unica'   => 'Selección única',
                            'abierta' => 'Abierta',
                        ])
                        ->required()
                        ->reactive(),

                    Forms\Components\Textarea::make('enunciado')
                        ->label('Enunciado')
                        ->required(),

                    // Checkbox para VF
                    Forms\Components\Toggle::make('correcto')
                        ->label('Respuesta correcta')
                        ->visible(fn (Forms\Get $get) => $get('tipo') === 'vf'),

                    // Opciones para única
                    Forms\Components\Repeater::make('opciones')
                        ->label('Opciones')
                        ->schema([
                            TextInput::make('texto')
                                ->label('Texto')
                                ->required(),
                            Forms\Components\Toggle::make('correcta')
                                ->label('Correcta'),
                        ])
                        ->columns(2)
                        ->minItems(fn (Forms\Get $get) =>
                            $get('tipo') === 'unica' ? 2 : 0
                        )
                        ->visible(fn (Forms\Get $get) => $get('tipo') === 'unica'),
                ])
                ->default([])
                ->columnSpanFull()
                ->collapsed()
                ->helperText('Deja vacío para no incluir preguntas.'),
        ])->columns(2);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->defaultSort('orden')
            ->reorderable('orden')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Nueva clase')
                    ->mutateFormDataUsing(fn (array $data) => $data + [
                        'orden' => $this->getOwnerRecord()->sesiones()->max('orden') + 1,
                    ]),
            ])
            ->columns([
                ImageColumn::make('miniatura')
                    ->label('Miniatura')
                    ->rounded()
                    ->square(),

                TextColumn::make('orden')
                    ->label('#')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('titulo')
                    ->wrap()
                    ->searchable(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }

    // solo admins/superadmins
    protected function onlyAdmins(): bool
    {
        $u = auth()->user();
        return $u->hasRole('superadmin') || $u->hasRole('admin');
    }

    public function canView(Model $record): bool    { return $this->onlyAdmins(); }
    public function canViewAny(): bool             { return $this->onlyAdmins(); }
    public function canCreate(): bool              { return $this->onlyAdmins(); }
    public function canEdit(Model $record): bool    { return $this->onlyAdmins(); }
    public function canDelete(Model $record): bool  { return $this->onlyAdmins(); }
}
