<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@test.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@test.com',
                'password' => Hash::make('123456'),
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Técnico',
                'email' => 'tecnico@test.com',
                'password' => Hash::make('123456'),
                'role' => 'tecnico',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ciudadano',
                'email' => 'ciudadano@test.com',
                'password' => Hash::make('123456'),
                'role' => 'ciudadano',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
