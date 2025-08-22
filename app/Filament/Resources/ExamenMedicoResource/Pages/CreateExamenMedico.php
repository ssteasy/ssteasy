<?php

namespace App\Filament\Resources\ExamenMedicoResource\Pages;

use App\Filament\Resources\ExamenMedicoResource;
use App\Models\{ExamenMedico, User};
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateExamenMedico extends CreateRecord
{
    protected static string $resource = ExamenMedicoResource::class;

    /**
     *  Sobrecargamos la lógica de guardado para soportar asignación masiva
     */
    protected function handleRecordCreation(array $data): ExamenMedico
    {
        // Extraemos los parámetros de asignación
        $isMassive = $data['masivo'] ?? false;
        $cargoIds  = $data['cargo_ids'] ?? [];
        $sedeIds   = $data['sede_ids']  ?? [];
        $sexo      = $data['sexo']      ?? null;

        // Campos auxiliares que no existen en la tabla
        unset($data['masivo'], $data['cargo_ids'], $data['sede_ids'], $data['sexo']);

        if (! $isMassive) {
            // Asignación individual (comportamiento tradicional)
            return ExamenMedico::create($data);
        }

        /* ---------------------------------------------------------
         *  ASIGNACIÓN MASIVA
         * --------------------------------------------------------*/
        DB::transaction(function () use ($data, $cargoIds, $sedeIds, $sexo) {
            $users = User::query()
                ->when($cargoIds, fn ($q) => $q->whereIn('cargo_id', $cargoIds))
                ->when($sedeIds,  fn ($q) => $q->whereIn('sede_id',  $sedeIds))
                ->when($sexo,     fn ($q) => $q->where('sexo', $sexo))
                ->when(
                    auth()->user()->hasRole('admin'),
                    fn ($q) => $q->where('empresa_id', auth()->user()->empresa_id)
                )
                ->get();

            foreach ($users as $user) {
                ExamenMedico::create(array_merge($data, ['user_id' => $user->id]));
            }

            $this->notify(
                'success',
                "Se asignaron {$users->count()} exámenes médicos correctamente."
            );
        });

        // Redirigimos al listado porque no hay “un” registro que editar
        $this->redirect(ExamenMedicoResource::getUrl());

        // Retornamos un modelo vacío solo para cumplir la firma
        return new ExamenMedico();
    }
}
