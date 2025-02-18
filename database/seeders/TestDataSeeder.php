<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Infraestructura;
use App\Models\Incidencia;
use App\Models\User;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Crear algunas infraestructuras
        $infraestructuras = [
            [
                'tipo' => 'Contenedor de Basura',
                'ubicacion' => 'Av. Principal 123',
                'descripcion' => 'Contenedor principal del sector',
                'estado' => 'operativo',
            ],
            [
                'tipo' => 'Luminaria',
                'ubicacion' => 'Plaza Central',
                'descripcion' => 'Poste de luz LED',
                'estado' => 'mantenimiento',
            ],
            [
                'tipo' => 'Semáforo',
                'ubicacion' => 'Intersección Principal',
                'descripcion' => 'Semáforo de 4 vías',
                'estado' => 'operativo',
            ],
        ];

        foreach ($infraestructuras as $infra) {
            Infraestructura::create($infra);
        }

        // Obtener usuarios
        $tecnico = User::where('role', 'tecnico')->first();
        $ciudadano = User::where('role', 'ciudadano')->first();

        // Crear algunas incidencias
        $incidencias = [
            [
                'tipo' => 'Contenedor Lleno',
                'ubicacion' => 'Av. Principal 123',
                'descripcion' => 'El contenedor está desbordado',
                'fecha' => now(),
                'estado' => 'pendiente',
                'prioridad' => 'alta',
                'latitud' => -33.4489,
                'longitud' => -70.6693,
                'infraestructura_id' => 1,
                'tecnico_id' => $tecnico?->id,
                'ciudadano_id' => $ciudadano?->id,
            ],
            [
                'tipo' => 'Luz Dañada',
                'ubicacion' => 'Plaza Central',
                'descripcion' => 'La luminaria no enciende',
                'fecha' => now(),
                'estado' => 'en_proceso',
                'prioridad' => 'media',
                'latitud' => -33.4500,
                'longitud' => -70.6700,
                'infraestructura_id' => 2,
                'tecnico_id' => $tecnico?->id,
                'ciudadano_id' => $ciudadano?->id,
            ],
            [
                'tipo' => 'Semáforo Intermitente',
                'ubicacion' => 'Intersección Principal',
                'descripcion' => 'El semáforo está en amarillo intermitente',
                'fecha' => now(),
                'estado' => 'pendiente',
                'prioridad' => 'critica',
                'latitud' => -33.4511,
                'longitud' => -70.6711,
                'infraestructura_id' => 3,
                'tecnico_id' => null,
                'ciudadano_id' => $ciudadano?->id,
            ],
        ];

        foreach ($incidencias as $incidencia) {
            Incidencia::create($incidencia);
        }
    }
}
