<?php

namespace Database\Seeders;

use App\Models\Incidencia;
use App\Models\Infraestructura;
use App\Models\User;
use Illuminate\Database\Seeder;

class IncidenciaSeeder extends Seeder
{
    public function run()
    {
        // Asegurarnos de que exista una infraestructura
        $infraestructura = Infraestructura::first();
        if (!$infraestructura) {
            $infraestructura = Infraestructura::create([
                'tipo' => 'Parque',
                'ubicacion' => 'Av. Principal #123',
                'descripcion' => 'Parque principal de la ciudad',
                'estado' => 'operativo',
                'ultima_revision' => now(),
                'historial_mantenimiento' => json_encode([])
            ]);
        }

        // Asegurarnos de que exista un ciudadano
        $ciudadano = User::where('role', 'ciudadano')->first();
        if (!$ciudadano) {
            $ciudadano = User::create([
                'name' => 'Ciudadano Test',
                'email' => 'ciudadano@test.com',
                'password' => bcrypt('password'),
                'role' => 'ciudadano'
            ]);
        }

        // Crear incidencia de prueba
        Incidencia::create([
            'tipo' => 'Mantenimiento',
            'ubicacion' => 'Av. Principal #123',
            'descripcion' => 'Luminarias dañadas en el parque',
            'fecha' => now(),
            'estado' => 'pendiente',
            'prioridad' => 'media',
            'latitud' => -33.4569,
            'longitud' => -70.6483,
            'infraestructura_id' => $infraestructura->id,
            'ciudadano_id' => $ciudadano->id
        ]);
    }
}
