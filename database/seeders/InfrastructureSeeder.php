<?php

namespace Database\Seeders;

use App\Models\Infrastructure;
use Illuminate\Database\Seeder;

class InfrastructureSeeder extends Seeder
{
    public function run()
    {
        $infrastructures = [
            [
                'nombre' => 'Parque Simón Bolívar',
                'descripcion' => 'Parque metropolitano',
                'latitude' => 4.6580,
                'longitude' => -74.0935
            ],
            [
                'nombre' => 'Plaza de Bolívar',
                'descripcion' => 'Plaza principal',
                'latitude' => 4.5981,
                'longitude' => -74.0758
            ]
        ];

        foreach ($infrastructures as $infrastructure) {
            Infrastructure::create($infrastructure);
        }
    }
}