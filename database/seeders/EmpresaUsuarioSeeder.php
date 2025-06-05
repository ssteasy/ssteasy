<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\User;
use Spatie\Permission\Models\Role;

class EmpresaUsuarioSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear la empresa
        //$empresa = Empresa::create([
         //   'nombre' => 'SST Easy Solutions',
         //   'nit' => '900123456-7',
          ////////  'razon_social' => 'SST Easy Solutions SAS',
          //////  'telefono' => '6011234567',
          ////  'direccion' => 'Calle 123 #45-67',
          //  'ciudad' => 'Bogotá',
         //   'activo' => true
        //]);

        // 2. Crear usuario admin para la empresa
        $usuario = User::create([
            'primer_nombre' => 'Colaborador',
            'primer_apellido' => 'user',
            'email' => 'admin@ssteasy.com',
            'password' => bcrypt('654321'),
            'tipo_documento' => 'Cédula de ciudadanía',
            'numero_documento' => '1234567890',
            'sexo' => 'Masculino',
            'telefono' => '3007654321',
            'direccion' => 'Calle 123 #45-67',
            'pais_dane' => '169',
            'departamento_dane' => '11001',
            'municipio_dane' => '11001',
            'zona' => 'Urbana',
            'cargo_id' => 1, // Asumiendo que existe un cargo con id 1
            'tipo_contrato' => 'Indefinido',
            'modalidad' => 'Presencial',
            'nivel_riesgo' => 'Riesgo I',
            'eps' => '11001',
            'ips' => '11001',
            'arl' => '11001',
            'afp' => '11001',
        ]);

        // 3. Asignar rol de admin
        $rolAdmin = Role::where('name', 'admin')->first();
        $usuario->assignRole($rolAdmin);

        $this->command->info('¡Empresa y usuario admin creados exitosamente!');
        $this->command->info('Usuario: admin@ssteasy.com / Password123!');
    }
}