<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaneCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
// database/seeders/DaneCatalogSeeder.php
public function run()
{
    // Paises (ejemplo abreviado)
    \App\Models\Pais::insert([
        ['codigo'=>'CO', 'nombre'=>'Colombia'],
        ['codigo'=>'US', 'nombre'=>'Estados Unidos'],
        // ...
    ]);

    // Departamentos (ejemplo)
    \App\Models\Departamento::insert([
        ['codigo'=>'05', 'nombre'=>'Antioquia', 'pais_codigo'=>'CO'],
        ['codigo'=>'08', 'nombre'=>'Atlántico', 'pais_codigo'=>'CO'],
        // ...
    ]);

    // Municipios (ejemplo)
    \App\Models\Municipio::insert([
        ['codigo'=>'05001', 'nombre'=>'Medellín',           'departamento_codigo'=>'05'],
        ['codigo'=>'05002', 'nombre'=>'Abejorral',          'departamento_codigo'=>'05'],
        ['codigo'=>'08001', 'nombre'=>'Barranquilla',       'departamento_codigo'=>'08'],
        // ...
    ]);
}

}
