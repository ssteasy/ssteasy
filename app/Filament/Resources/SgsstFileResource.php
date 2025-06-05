<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SgsstFileResource\Pages;
use App\Models\{Empresa, SgsstFile, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

use Filament\Notifications\Notification;

use Filament\Facades\Filament;
use Filament\Resources\Resource;

//  Form & Table aliases
use Filament\Forms\Form as FilamentForm;
use Filament\Tables\Table as FilamentTable;

// ── Form components ─────────────────────────────────────────────
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    DatePicker,
    FileUpload,
    Select,
    Hidden,
    Toggle
};

// ── Table columns ───────────────────────────────────────────────
use Filament\Tables\Columns\{
    TextColumn,
    IconColumn,
    BadgeColumn
};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\BooleanFilter;
use Filament\Tables\Filters\Filter;


// ── Table actions ───────────────────────────────────────────────
use Filament\Tables\Actions\{
    Action,
    ViewAction,
    EditAction,
    DeleteAction,
    DeleteBulkAction
};
use Carbon\Carbon;




class SgsstFileResource extends Resource
{
    protected static ?string $model = SgsstFile::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'Gestión de Archivos';
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Filament::auth()->user();

        // ── Admin → archivos de SU empresa
        if ($user->hasRole('admin')) {
            return $query->where('empresa_id', $user->empresa_id);
        }

        // ── Colaborador → sólo los que debe firmar
        if ($user->hasRole('colaborador')) {
            return $query->whereHas(
                'assignedUsers',
                fn($q) =>
                $q->where('users.id', $user->id)
            );
        }

        // ── Super-admin → sin restricción
        return $query;
    }


    // ────────────────────────── FORM ──────────────────────────
    public static function form(FilamentForm $form): FilamentForm
    {
        return $form->schema([
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




            // Quien sube
            Hidden::make('uploaded_by')
                ->default(fn() => Filament::auth()->id()),

            TextInput::make('title')->label('Título')->required(),


            FileUpload::make('file_path')
                ->label('Archivo')
                ->disk('public')
                ->directory('sgsst-files')
                ->required(),
            Select::make('assignedUsers')
                ->label('Asignar colaboradores')
                ->multiple()
                ->relationship(
                    'assignedUsers',
                    'primer_nombre',
                    function (Builder $query) {
                        $user = Filament::auth()->user();

                        // Si hay usuario y NO es superadmin, filtra por su empresa
                        if ($user && !$user->hasRole('superadmin')) {
                            $query->where('empresa_id', $user->empresa_id);
                        }

                        // Solo colaboradores
                        $query->whereHas('roles', fn($q) => $q->where('name', 'colaborador'));
                    }
                )
                ->preload()
                ->required(),
            Textarea::make('description')->label('Descripción')->rows(3),
            Toggle::make('require_signature')
                ->label('Requiere firma de colaboradores')
                ->inline(false)
                ->default(false)
                ->live(),



            DatePicker::make('signature_deadline')
                ->label('Fecha límite de firma')
                ->visible(fn($get) => $get('require_signature'))
                ->nullable()
                ->reactive(),
        ]);
    }

    // ────────────────────────── TABLE ──────────────────────────
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
                        // Si es null (fase de encabezado) => ocultar
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
                    ->getStateUsing(fn($record) => $record->signatories()->whereNotNull('signed_at')->count())
                    ->color('success')
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),

                BadgeColumn::make('pending_count')
                    ->label('Pendientes')
                    ->getStateUsing(fn($record) => $record->signatories()->whereNull('signed_at')->count())
                    ->color('danger')
                    ->visible(fn() => Filament::auth()->user()->hasAnyRole(['admin', 'superadmin'])),


            ])
            ->actions([
                /* ========= Firma / listado ========= */
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
                    /* ── modal de confirmación ─────────────────────────── */
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar firma')
                    ->modalDescription('¿Está seguro de que desea firmar este documento?')
                    ->modalSubmitActionLabel('Firmar')
                    ->modalCancelActionLabel('Cancelar')

                    /* ── acción de firmado ─────────────────────────────── */
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

                /* ========= Descargar =========== */
                Action::make('download-direct')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn($record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),

                /* ========= Preview (ver / firmar) ========= */
                Action::make('preview')
                    ->label(label: 'Ver')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => $record->title)
                    ->modalWidth('full')
                    ->modalSubmitAction(false)
                    ->modalContent(fn($record) => view(
                        'filament.components.file-preview',
                        [
                            'record'  => $record->load('signatories', 'assignedUsers'),
                            'canSign' => $record->require_signature
                                && $record->signatories()
                                ->where('user_id', Filament::auth()->id())
                                ->whereNull('signed_at')
                                ->exists(),
                        ]
                    )),

                /* ========= Edit / Delete sólo admin ========= */
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

    // ────────────────────────── PAGES ──────────────────────────
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSgsstFiles::route('/'),
            'create' => Pages\CreateSgsstFile::route('/create'),
            'edit' => Pages\EditSgsstFile::route('/{record}/edit'),
        ];
    }

    // ────────────────────────── QUERY SCOPING ──────────────────

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }
        $data['require_signature'] = $data['require_signature'] ?? false;
        if (! $data['require_signature']) {
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
        if (! $data['require_signature']) {
            $data['signature_deadline'] = null;
        }
        return $data;
    }
    public static function canCreate(): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }

    /** Solo los ADMIN pueden editar */
    public static function canEdit(Model $record): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }

    /** Solo los ADMIN pueden borrar */
    public static function canDelete(Model $record): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }

    /** Solo los ADMIN pueden borrar masivamente */
    public static function canDeleteAny(): bool
    {
        return Filament::auth()->user()?->hasRole('admin');
    }
}
