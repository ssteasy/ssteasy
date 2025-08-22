<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SgsstFileResource\Pages;
use App\Models\{Empresa, SgsstFile, User, Cargo, Sede};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Facades\Filament;
use Filament\Forms\Form as FilamentForm;
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    DatePicker,
    FileUpload,
    Select,
    Hidden,
    Toggle,
    Fieldset
};
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table as FilamentTable;
use Filament\Tables\Columns\{
    TextColumn,
    IconColumn,
    BadgeColumn
};
use Filament\Tables\Actions\{
    Action,
    ViewAction,
    EditAction,
    DeleteAction,
    DeleteBulkAction
};
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;
use Closure;

class SgsstFileResource extends Resource
{
    /* ─────────────────────────────── Básicos ─────────────────────────────── */

    protected static ?string $model = SgsstFile::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Documentación de procesos';
    protected static ?string $navigationLabel = 'Gestión de Archivos';

    /* ─────────────────────── Scope según rol del usuario ─────────────────── */

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Filament::auth()->user();

        if ($user->hasRole('admin')) {
            return $query->where('empresa_id', $user->empresa_id);
        }

        if ($user->hasRole('colaborador')) {
            return $query->whereHas(
                'assignedUsers',
                fn($q) =>
                $q->where('users.id', $user->id)
            );
        }

        return $query; // superadmin
    }

    /* ───────────────────── Helper: IDs de asignación masiva ──────────────── */

    protected static function refreshAssignees(Get $get): array   // ⬅️ cambia Closure ↦ Get
    {
        $query = User::query()
            ->whereHas('roles', fn($q) => $q->where('name', 'colaborador'));

        $empresaId = $get('empresa_id') ?? Filament::auth()->user()->empresa_id;
        $query->where('empresa_id', $empresaId);

        $query->when($get('filter_cargo_id'), fn($q, $id) => $q->where('cargo_id', $id));
        $query->when($get('filter_sede_id'), fn($q, $id) => $q->where('sede_id', $id));
        $query->when($get('filter_sexo'), fn($q, $sexo) => $q->where('sexo', $sexo));

        return $query->pluck('id')->all();
    }

    /* ──────────────────────────────── FORM ───────────────────────────────── */

    public static function form(FilamentForm $form): FilamentForm
    {
        return $form->schema([
            /* ── Empresa (visible sólo para superadmin) ─────────────────── */
            Select::make('empresa_id')
                ->label('Empresa')
                ->options(Empresa::pluck('nombre', 'id'))
                ->searchable()
                ->required()
                ->default(fn() => auth()->user()->empresa_id)
                ->visible(fn() => auth()->user()->hasRole('superadmin')),
            Hidden::make('empresa_id')
                ->default(fn() => auth()->user()->empresa_id)
                ->visible(fn() => !auth()->user()->hasRole('superadmin')),

            /* ── Quien sube ─────────────────────────────────────────────── */
            Hidden::make('uploaded_by')->default(fn() => Filament::auth()->id()),

            /* ── Datos básicos del archivo ──────────────────────────────── */
            TextInput::make('title')->label('Título')->required(),
            Textarea::make('description')->label('Descripción')->rows(3),

            DatePicker::make('signature_deadline')
                ->label('Fecha límite de firma')
                ->visible(fn($get) => $get('require_signature'))
                ->nullable()
                ->reactive(),



            Toggle::make('require_signature')
                ->label('Requiere firma de colaboradores')
                ->inline(false)
                ->default(false)
                ->live(),

            FileUpload::make('file_path')
                ->label('Archivo')
                ->disk('public')
                ->directory('sgsst-files')
                ->required(),

            /* ────────────────── Filtros de asignación masiva ───────────── */


            /* ── Asignar colaboradores (relleno automático) ─────────────── */
            Select::make('assignedUsers')
                ->columns(2)
                ->label('Asignar colaboradores')
                ->multiple()
                ->relationship('assignedUsers', 'primer_nombre', function (Builder $query) {
                    $user = Filament::auth()->user();

                    if ($user && !$user->hasRole('superadmin')) {
                        $query->where('empresa_id', $user->empresa_id);
                    }

                    $query->whereHas('roles', fn($q) => $q->where('name', 'colaborador'));
                })
                ->preload()
                ->required(),
            Fieldset::make('Filtros avanzados de Asignación a colaboradores')
                ->columns(3)
                ->dehydrated(false) // no persiste en DB
                ->schema([
                    // Cargo
                    Select::make('filter_cargo_id')
                        ->label('Cargo')
                        ->options(
                            Cargo::query()
                                ->where('empresa_id', auth()->user()->empresa_id)
                                ->pluck('nombre', 'id')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(
                            fn($set, $get) =>
                            $set('assignedUsers', self::refreshAssignees($get))
                        )
                        ->dehydrated(false),

                    // Sede
                    Select::make('filter_sede_id')
                        ->label('Sede')
                        ->options(
                            Sede::query()
                                ->where('empresa_id', auth()->user()->empresa_id)
                                ->pluck('nombre', 'id')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(
                            fn($set, $get) =>
                            $set('assignedUsers', self::refreshAssignees($get))
                        )
                        ->dehydrated(false),

                    // Sexo
                    Select::make('filter_sexo')
                        ->label('Sexo')
                        ->options([
                            'Masculino' => 'Masculino',
                            'Femenino' => 'Femenino',
                            'Otro' => 'Otro',
                        ])
                        ->reactive()
                        ->afterStateUpdated(
                            fn($set, $get) =>
                            $set('assignedUsers', self::refreshAssignees($get))
                        )
                        ->dehydrated(false),
                ]),
        ]);
    }

    /* ─────────────────────────────── TABLE ──────────────────────────────── */

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                TextColumn::make('empresa.nombre')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => Filament::auth()->user()->hasRole('superadmin')),

                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('uploader.primer_nombre')->label('Subido por'),
                TextColumn::make('created_at')->label('Fecha subida')->date(),
                TextColumn::make('signature_deadline')->label('Límite firma')->date(),

                IconColumn::make('deadline_status')
                    ->label('Plazo')
                    ->tooltip('Plazo vencido')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(function ($record) {
                        if (!$record) {
                            return false;
                        }

                        return $record->require_signature
                            && $record->signature_deadline
                            && Carbon::parse($record->signature_deadline)->isPast()
                            && $record->signatories()->whereNull('signed_at')->exists();
                    }),

                BadgeColumn::make('signatories_count')
                    ->label('Asignados')
                    ->counts('signatories')
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),

                BadgeColumn::make('signed_count')
                    ->label('Firmados')
                    ->getStateUsing(
                        fn($record) =>
                        $record->signatories()->whereNotNull('signed_at')->count()
                    )
                    ->color('success')
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),

                BadgeColumn::make('pending_count')
                    ->label('Pendientes')
                    ->getStateUsing(
                        fn($record) =>
                        $record->signatories()->whereNull('signed_at')->count()
                    )
                    ->color('danger')
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),
            ])

            ->actions([
                /* Firmas / listado */
                Action::make('signatures')
                    ->label('Firmas')
                    ->icon('heroicon-o-users')
                    ->modalWidth('lg')
                    ->modalHeading('Firmas del documento')
                    ->modalSubmitAction(false)
                    ->modalContent(fn($record) => view(
                        'filament.components.file-signatures',
                        ['record' => $record->load('assignedUsers', 'signatories')]
                    ))
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),

                /* Firmar */
                Action::make('sign')
                    ->label('Firmar')
                    ->icon('heroicon-o-pencil')
                    ->visible(
                        fn($record) =>
                        Filament::auth()->user()->hasRole('colaborador')
                        && $record->assignedUsers->contains(Filament::auth()->id())
                        && $record->require_signature
                        && $record->signatories()
                            ->where('user_id', Filament::auth()->id())
                            ->whereNull('signed_at')
                            ->exists()
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar firma')
                    ->modalDescription('¿Está seguro de que desea firmar este documento?')
                    ->modalSubmitActionLabel('Firmar')
                    ->modalCancelActionLabel('Cancelar')
                    ->action(function ($record) {
                        $record->signatories()
                            ->where('user_id', Filament::auth()->id())
                            ->update(['signed_at' => now()]);

                        Notification::make()
                            ->title('Documento firmado')
                            ->body('Has firmado el documento correctamente.')
                            ->success()
                            ->send();
                    }),

                /* Descargar */
                Action::make('download-direct')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn($record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),

                /* Preview */
                Action::make('preview')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => $record->title)
                    ->modalWidth('full')
                    ->modalSubmitAction(false)
                    ->modalContent(fn($record) => view(
                        'filament.components.file-preview',
                        [
                            'record' => $record->load('signatories', 'assignedUsers'),
                            'canSign' => $record->require_signature
                                && $record->signatories()
                                    ->where('user_id', Filament::auth()->id())
                                    ->whereNull('signed_at')
                                    ->exists(),
                        ]
                    )),

                EditAction::make()
                    ->visible(fn() => Filament::auth()->user()->hasRole('admin')),

                DeleteAction::make()
                    ->visible(fn() => Filament::auth()->user()->hasRole('admin')),
            ])

            ->filters([
                SelectFilter::make('empresa_id')
                    ->label('Empresa')
                    ->options(Empresa::pluck('nombre', 'id'))
                    ->visible(fn() => Filament::auth()->user()->hasRole('superadmin')),
            ])

            ->bulkActions([
                DeleteBulkAction::make()
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),
            ]);
    }

    /* ─────────────────────────────── PAGES ──────────────────────────────── */

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSgsstFiles::route('/'),
            'create' => Pages\CreateSgsstFile::route('/create'),
            'edit' => Pages\EditSgsstFile::route('/{record}/edit'),
        ];
    }

    /* ─────────────────────────── Mutators / Gates ───────────────────────── */

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }

        $data['require_signature'] = $data['require_signature'] ?? false;
        if (!$data['require_signature']) {
            $data['signature_deadline'] = null;
        }

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (!auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }

        $data['require_signature'] = $data['require_signature'] ?? false;
        if (!$data['require_signature']) {
            $data['signature_deadline'] = null;
        }

        return $data;
    }

    /* ─────────────────────────────― Policies ―──────────────────────────── */

    public static function canCreate(): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }

    public static function canEdit(Model $record): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }

    public static function canDelete(Model $record): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }

    public static function canDeleteAny(): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }
}
