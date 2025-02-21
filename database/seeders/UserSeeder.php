<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear el usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@ciudadlimpia.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        // Asignar rol de administrador
        if ($role = Role::where('name', 'Administrador')->first()) {
            $admin->assignRole($role);
        }

        // Crear el usuario técnico
        $tech = User::create([
            'name' => 'Técnico',
            'email' => 'tecnico@ciudadlimpia.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        // Asignar rol de técnico
        if ($role = Role::where('name', 'Técnico')->first()) {
            $tech->assignRole($role);
        }

        // Crear el usuario ciudadano
        $citizen = User::create([
            'name' => 'Ciudadano',
            'email' => 'ciudadano@ciudadlimpia.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        // Asignar rol de ciudadano
        if ($role = Role::where('name', 'Ciudadano')->first()) {
            $citizen->assignRole($role);
        }
    }
}