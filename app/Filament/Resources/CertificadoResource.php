<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificadoResource\Pages;
use App\Models\Certificado;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;

class CertificadoResource extends Resource
{
    protected static ?string $model = Certificado::class;

    protected static ?string $navigationLabel = 'Certificados';
    protected static ?string $navigationIcon   = 'heroicon-o-newspaper';
    
    protected static ?string $navigationGroup  = 'Capacitaciones';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        // No permitimos crear ni editar certificados manualmente en el panel.
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('created_at')->label('Fecha emisión')->dateTime()->sortable(),
                TextColumn::make('user')
                    ->label('Colaborador')
                    ->getStateUsing(
                        fn(Certificado $record) =>
                        "{$record->user->primer_nombre} {$record->user->primer_apellido}"
                    )
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.numero_documento')->label('Documento'),
                TextColumn::make('capacitacion.nombre_capacitacion')
                    ->label('Curso')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('codigo_unico')->label('Código')->copyable(),
                //IconColumn::make('file_path')
                    //->label('Archivo')
                    //->boolean()
                    //->getStateUsing(fn(Certificado $record) =>
                    //    Storage::disk('public')->exists($record->file_path)
                    //)
                    //->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                
            ])
            ->bulkActions([
              
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificados::route('/'),
            // Deshabilitamos create y edit:
            //'create' => Pages\CreateCertificado::route('/create'),
            //'edit'   => Pages\EditCertificado::route('/{record}/edit'),
        ];
    }
}
