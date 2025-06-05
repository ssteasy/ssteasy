<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $colaborador = Role::create(['name' => 'colaborador']);

        // Crear permisos
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'ver reportes']);
        Permission::create(['name' => 'gestionar empresa']);

        // Asignar permisos
        $superadmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(['crear usuarios', 'ver reportes']);
        $colaborador->givePermissionTo(['ver reportes']);
    }
}