<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Modalidad;

class ModalidadSeeder extends Seeder
{
    public function run(): void
    {
        $default = [
            'Presencial',
            'Teletrabajo',
            'Trabajo Remoto',
            'Trabajo en casa',
        ];

        // Para cada empresa existente, crea (si no existe) las modalidades
        Empresa::all()->each(function (Empresa $empresa) use ($default) {
            foreach ($default as $nombre) {
                Modalidad::firstOrCreate([
                    'empresa_id' => $empresa->id,
                    'nombre'     => $nombre,
                ], [
                    'activo'     => true,
                ]);
            }
        });
    }
}
