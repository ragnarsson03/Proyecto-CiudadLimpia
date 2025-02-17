<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@ciudadlimpia.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Técnico',
            'email' => 'tecnico@ciudadlimpia.com',
            'password' => Hash::make('password'),
            'role' => 'tecnico',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Ciudadano',
            'email' => 'ciudadano@ciudadlimpia.com',
            'password' => Hash::make('password'),
            'role' => 'ciudadano',
            'email_verified_at' => now(),
        ]);
    }
}
