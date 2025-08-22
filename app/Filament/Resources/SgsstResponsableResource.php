<?php
namespace App\Filament\Resources;

use App\Filament\Resources\SgsstResponsableResource\Pages;
use App\Filament\Resources\SgsstResponsableResource\RelationManagers;

use App\Models\SgsstResponsable;
use App\Models\User;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    Select,      // ← aquí
    DatePicker,
    Textarea,
    FileUpload,
    Toggle,
    Repeater,
    TextInput
};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;


use Filament\Pages\Actions\CreateAction;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;




class SgsstResponsableResource extends Resource
{
    protected static ?string $model = SgsstResponsable::class;
    
    protected static ?string $navigationGroup = 'Generalidades';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Si es superadmin, mostrar el selector
                Select::make('empresa_id')
                    ->label('Empresa')
                    ->relationship('empresa', 'nombre')
                    ->visible(fn() => auth()->user()->hasRole('superadmin'))
                    ->required(),

                Select::make('user_id')
                    ->label('Responsable SST')
                    ->required()
                    ->searchable()
                    ->live()
                    ->getSearchResultsUsing(function (string $search, callable $get) {
                        $empresaId = auth()->user()->hasRole('superadmin')
                            ? $get('empresa_id')
                            : auth()->user()->empresa_id;

                        if (!$empresaId) {
                            return [];
                        }

                        return \App\Models\User::query()
                            ->where('empresa_id', $empresaId)
                            ->where(function ($query) use ($search) {
                                $query->where('primer_nombre', 'like', "%{$search}%")
                                    ->orWhere('numero_documento', 'like', "%{$search}%");
                            })
                            ->limit(20)
                            ->get()
                            ->mapWithKeys(function ($user) {
                                return [$user->id => "{$user->primer_nombre} - {$user->numero_documento}"];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $user = \App\Models\User::find($value);
                        return $user ? "{$user->primer_nombre} - {$user->numero_documento}" : null;
                    })
                ,

                DatePicker::make('fecha_inicio')->label('Fecha inicio')->required(),
                DatePicker::make('fecha_fin')->label('Fecha fin')->nullable(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),

                TextArea::make('funciones')
                    ->label('Funciones')
                    ->required(),

                Repeater::make('documentos')
                    ->label('Documentos')
                    ->schema([
                        TextInput::make('titulo')->label('Título')->required(),
                        FileUpload::make('file')
                            ->label('Archivo')
                            ->disk('public')
                            ->directory('sgsst-responsables')
                            ->required(),
                    ])
                    ->createItemButtonLabel('Agregar documento')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.profile_photo_path')
                    ->label('Foto')
                    ->circular()
                    ->size(32),

                TextColumn::make('user')
                    ->label('Responsable')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        "{$record->user->primer_nombre} {$record->user->segundo_nombre} {$record->user->primer_apellido} {$record->user->segundo_apellido}"
                    )
                    ->sortable()
                    ->searchable(),

                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date()
                    ->sortable(),

                TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),

                TextColumn::make('funciones')
                    ->label('Funciones')
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('documentos')
                    ->label('Docs')
                    ->formatStateUsing(fn($state, $record) => count($record->documentos ?? []) . ' archivo(s)'),
            ])
            ->filters([
                SelectFilter::make('empresa_id')
                    ->label('Empresa')
                    ->relationship('empresa', 'nombre')
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
            ])
            ->actions([
                EditAction::make()
                    ->visible(fn() => auth()->user()->hasAnyRole(['admin', 'superadmin'])),
                DeleteAction::make()
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
            ]);

    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('admin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }
        return $data;
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        // Sólo admins de empresa y superadmins
        if (!auth()->user()->hasRole('superadmin')) {
            $query->whereHas(
                'user',
                fn($q) =>
                $q->where('empresa_id', auth()->user()->empresa_id)
            );
        }
        return $query;
    }
    public static function getWidgets(): array
    {
        return [
            \App\Filament\Resources\SgsstResponsableResource\Widgets\ResponsableSstInfo::class,
        ];
    }

    public static function index(): string
    {
        return static::getPages()['index'];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSgsstResponsables::route('/'),
            'create' => Pages\CreateSgsstResponsable::route('/create'),
            'edit' => Pages\EditSgsstResponsable::route('/{record}/edit'),
        ];
    }
}
