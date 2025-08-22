<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificadoResource\Pages;
use App\Models\Certificado;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;

class CertificadoResource extends Resource
{
    protected static ?string $model = Certificado::class;

    protected static ?string $navigationLabel = 'Certificados';
    protected static ?string $navigationIcon  = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Gestión del conocimiento';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        // No permitimos crear ni editar certificados manualmente.
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha emisión')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('user')
                    ->label('Colaborador')
                    ->getStateUsing(fn (Certificado $r) =>
                        "{$r->user->primer_nombre} {$r->user->primer_apellido}"
                    )
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.numero_documento')
                    ->label('Documento'),

                TextColumn::make('capacitacion.nombre_capacitacion')
                    ->label('Curso')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('codigo_unico')
                    ->label('Código')
                    ->copyable(),
            ])
            ->actions([
                Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->tooltip('Descargar PDF del certificado')
                    ->url(fn (Certificado $r) =>
                        Storage::disk('public')->url($r->file_path)
                    )
                    ->openUrlInNewTab()   // abre el PDF en otra pestaña (descarga/visualiza)
                    ->visible(fn (Certificado $r) =>
                        Storage::disk('public')->exists($r->file_path)
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificados::route('/'),
        ];
    }
}
