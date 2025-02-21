<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        Role::create(['name' => 'Administrador']);
        Role::create(['name' => 'Técnico']);
        Role::create(['name' => 'Ciudadano']);
    }
}
