<?php
// app/Filament/Resources/ProfesiogramaResource/Pages/ViewProfesiograma.php

namespace App\Filament\Resources\ProfesiogramaResource\Pages;

use App\Filament\Resources\ProfesiogramaResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Components\{
    Grid,
    Group,
    Section,
    TextEntry,
    RepeatableEntry,
    ImageEntry
};
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;

class ViewProfesiograma extends ViewRecord
{
    protected static string $resource = ProfesiogramaResource::class;

    /* --------------------------------------------------------------
     *  FICHA DETALLADA
     * -------------------------------------------------------------- */
    public function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                /* ---------- Información general ---------- */
                Section::make('Información general')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextEntry::make('cargo.nombre')
                                    ->label('Cargo')
                                    ->weight(FontWeight::Bold),

                                TextEntry::make('created_at')
                                    ->label('Creado')
                                    ->dateTime('d M Y'),
                            ]),

                        TextEntry::make('tareas')
                            ->label('Tareas')
                            ->columnSpanFull(),

                        TextEntry::make('funciones')
                            ->label('Funciones')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                /* ---------- Vacunas ---------- */
                Section::make('Vacunas')
                    ->visible(fn ($record) => $record->vacunas()->exists())
                    ->schema([
                        RepeatableEntry::make('vacunas')
                            ->label('')
                            ->schema([
                                TextEntry::make('nombre'),
                            ])
                            ->columns(2),
                    ]),

                /* ---------- EPP ---------- */
                Section::make('Elementos de Protección Personal (EPP)')
                    ->visible(fn ($record) => $record->epps()->exists())
                    ->schema([
                        RepeatableEntry::make('epps')
                            ->label('')
                            ->schema([
                                TextEntry::make('nombre'),
                            ])
                            ->columns(2),
                    ]),

                /* ---------- Exámenes médicos ---------- */
                Section::make('Exámenes médicos requeridos')
                    ->visible(fn ($record) => $record->profesiogramaExamenTipos()->exists())
                    ->schema([
                        RepeatableEntry::make('profesiogramaExamenTipos')
                            ->label('')
                            ->schema([
                                Group::make()
                                    ->relationship('examenTipo')
                                    ->schema([
                                        TextEntry::make('nombre')
                                            ->weight(FontWeight::Bold)
                                            ->columnSpan(2),
                                    ]),

                                TextEntry::make('tipificacion')
                                    ->label('Tipificación'),

                                TextEntry::make('periodicidad_valor')
                                    ->label('Cada')
                                    ->formatStateUsing(fn ($state) => $state ?: '—'),

                                TextEntry::make('periodicidad_unidad')
                                    ->label('Unidad')
                                    ->formatStateUsing(fn ($state) => $state ?: '—'),
                            ])
                            ->columns(4),
                    ]),

                /* ---------- Adjuntos ---------- */
                Section::make('Adjuntos')
                    ->visible(fn ($record) => filled($record->adjuntos))
                    ->schema([
                        RepeatableEntry::make('adjuntos')
                            ->label('')
                            ->schema([
                                ImageEntry::make('')
                                    ->getStateUsing(fn ($state) => Storage::url($state)) // ← cambio clave
                                    ->extraAttributes([
                                        'class' => 'rounded-md shadow max-w-xs',
                                    ]),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    /* --------------------------------------------------------------
     *  Acciones de cabecera
     * -------------------------------------------------------------- */
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('edit')
                ->label('Editar')
                ->url($this->getResource()::getUrl('edit', ['record' => $this->record])),

            \Filament\Actions\Action::make('pdf')
                ->label('Descargar PDF')
                ->action(fn () =>
                    \App\Filament\Resources\ProfesiogramaResource\Pages\GeneratePdf::run($this->record)),
        ];
    }
}
