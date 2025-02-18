<?php

namespace Database\Seeders;

use App\Models\Incidencia;
use App\Models\Infraestructura;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class IncidenciaSeeder extends Seeder
{
    private $tipos = [
        'Parque' => [
            'ubicaciones' => ['Parque Central', 'Parque Norte', 'Parque Sur'],
            'incidencias' => ['Vandalismo', 'Basura Acumulada', 'Juegos Dañados'],
            'descripciones' => [
                'Se requiere mantenimiento urgente',
                'Necesita limpieza',
                'Equipamiento en mal estado'
            ]
        ],
        'Semáforo' => [
            'ubicaciones' => ['Intersección Principal', 'Cruce Comercial', 'Avenida Central'],
            'incidencias' => ['No Funciona', 'Luz Intermitente', 'Poste Dañado'],
            'descripciones' => [
                'No está funcionando correctamente',
                'Las luces están fallando',
                'Estructura dañada'
            ]
        ],
        'Contenedor' => [
            'ubicaciones' => ['Zona Residencial', 'Mercado Central', 'Centro Comercial'],
            'incidencias' => ['Lleno', 'Dañado', 'Mal Ubicado'],
            'descripciones' => [
                'Necesita ser vaciado',
                'Presenta daños estructurales',
                'Requiere reubicación'
            ]
        ],
        'Luminaria' => [
            'ubicaciones' => ['Calle Principal', 'Plaza Central', 'Avenida Comercial'],
            'incidencias' => ['Apagada', 'Intermitente', 'Poste Inclinado'],
            'descripciones' => [
                'No está funcionando',
                'Luz parpadea constantemente',
                'Necesita reparación'
            ]
        ]
    ];

    public function run()
    {
        // Crear infraestructuras de prueba
        foreach ($this->tipos as $tipo => $data) {
            foreach ($data['ubicaciones'] as $ubicacion) {
                Infraestructura::create([
                    'tipo' => $tipo,
                    'ubicacion' => $ubicacion,
                    'descripcion' => "Infraestructura tipo {$tipo} en {$ubicacion}",
                    'estado' => Arr::random(['operativo', 'mantenimiento', 'fuera_de_servicio']),
                    'ultima_revision' => now()->subDays(rand(1, 30)),
                    'historial_mantenimiento' => json_encode([])
                ]);
            }
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

        // Crear incidencias de prueba
        $prioridades = ['baja', 'media', 'alta'];
        $estados = ['pendiente', 'en_proceso', 'resuelto'];
        
        $infraestructuras = Infraestructura::all();
        
        foreach ($infraestructuras as $infraestructura) {
            $numIncidencias = rand(1, 5);
            
            for ($i = 0; $i < $numIncidencias; $i++) {
                Incidencia::create([
                    'tipo' => $this->getTipoIncidencia($infraestructura->tipo),
                    'ubicacion' => $infraestructura->ubicacion,
                    'descripcion' => $this->getDescripcion($infraestructura->tipo),
                    'fecha' => now()->subDays(rand(1, 30)),
                    'estado' => Arr::random($estados),
                    'prioridad' => Arr::random($prioridades),
                    'latitud' => -33.4569 + (rand(-1000, 1000) / 10000),
                    'longitud' => -70.6483 + (rand(-1000, 1000) / 10000),
                    'infraestructura_id' => $infraestructura->id,
                    'ciudadano_id' => $ciudadano->id
                ]);
            }
        }
    }

    private function getTipoIncidencia($tipoInfraestructura)
    {
        return Arr::random($this->tipos[$tipoInfraestructura]['incidencias']);
    }

    private function getDescripcion($tipoInfraestructura)
    {
        return Arr::random($this->tipos[$tipoInfraestructura]['descripciones']);
    }
}
