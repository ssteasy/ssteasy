<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Empresa;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Contracts\TranslatableContentDriver;

class EmpresaEmpleadosTable extends Component implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public Empresa $empresa;

    /** Implementación requerida por HasTable (v3.1+) */
    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null; // No manejamos traducciones aquí
    }

    /** Configuración de la tabla */
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(fn () => $this->empresa->users()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Alta')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginationPageOptions([10, 25, 50]);   // ← Cambio aquí
    }

    public function render()
    {
        return view('livewire.empresa-empleados-table');
    }
}
