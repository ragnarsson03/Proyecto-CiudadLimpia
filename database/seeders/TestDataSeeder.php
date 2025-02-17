<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Infraestructura;
use App\Models\Incidencia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear infraestructuras de prueba
        $infraestructuras = [
            [
                'tipo' => 'Semáforo Principal',
                'ubicacion' => 'Av. Libertador con Calle 5',
                'descripcion' => 'Semáforo de 4 vías con sistema inteligente de control de tráfico',
                'estado' => 'operativo',
                'ultima_revision' => now()->subDays(5),
                'historial_mantenimiento' => json_encode([
                    ['fecha' => '2024-02-01', 'descripcion' => 'Mantenimiento preventivo', 'tecnico' => 'Juan Pérez'],
                ]),
            ],
            [
                'tipo' => 'Alumbrado Público',
                'ubicacion' => 'Plaza Central',
                'descripcion' => 'Sistema de iluminación LED de la plaza principal',
                'estado' => 'operativo',
                'ultima_revision' => now()->subDays(10),
                'historial_mantenimiento' => json_encode([]),
            ],
            [
                'tipo' => 'Fuente Ornamental',
                'ubicacion' => 'Parque Municipal',
                'descripcion' => 'Fuente decorativa con sistema de iluminación',
                'estado' => 'mantenimiento',
                'ultima_revision' => now()->subDays(15),
                'historial_mantenimiento' => json_encode([
                    ['fecha' => '2024-01-15', 'descripcion' => 'Limpieza de filtros', 'tecnico' => 'María González'],
                ]),
            ],
            [
                'tipo' => 'Cámara de Seguridad',
                'ubicacion' => 'Esquina Av. Principal',
                'descripcion' => 'Cámara de vigilancia 360° con visión nocturna',
                'estado' => 'fuera_de_servicio',
                'ultima_revision' => now()->subDays(20),
                'historial_mantenimiento' => json_encode([]),
            ],
            [
                'tipo' => 'Estación de Bicicletas',
                'ubicacion' => 'Terminal de Transporte',
                'descripcion' => 'Estación de préstamo de bicicletas públicas',
                'estado' => 'fuera_de_servicio',
                'ultima_revision' => now()->subDays(25),
                'historial_mantenimiento' => json_encode([]),
            ],
        ];

        foreach ($infraestructuras as $infra) {
            Infraestructura::create($infra);
        }

        // Obtener usuarios para asignar incidencias
        $tecnico = User::where('role', 'tecnico')->first();
        $ciudadano = User::where('role', 'ciudadano')->first();

        // Crear incidencias de prueba
        $incidencias = [
            [
                'tipo' => 'Falla Eléctrica',
                'ubicacion' => 'Plaza Central',
                'descripcion' => 'Tres postes de luz no funcionan en el sector norte',
                'fecha' => now()->subDays(1),
                'estado' => 'pendiente',
                'prioridad' => 'alta',
                'infraestructura_id' => 2,
                'tecnico_id' => $tecnico->id,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Vandalismo',
                'ubicacion' => 'Parque Municipal',
                'descripcion' => 'Grafitis en la base de la fuente',
                'fecha' => now()->subDays(2),
                'estado' => 'en_proceso',
                'prioridad' => 'media',
                'infraestructura_id' => 3,
                'tecnico_id' => $tecnico->id,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Falla Técnica',
                'ubicacion' => 'Av. Libertador con Calle 5',
                'descripcion' => 'Semáforo intermitente en hora pico',
                'fecha' => now()->subDays(3),
                'estado' => 'resuelto',
                'prioridad' => 'critica',
                'infraestructura_id' => 1,
                'tecnico_id' => $tecnico->id,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Mantenimiento',
                'ubicacion' => 'Terminal de Transporte',
                'descripcion' => 'Sistema de bloqueo no funciona correctamente',
                'fecha' => now()->subDays(4),
                'estado' => 'pendiente',
                'prioridad' => 'baja',
                'infraestructura_id' => 5,
                'tecnico_id' => null,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Falla de Energía',
                'ubicacion' => 'Esquina Av. Principal',
                'descripcion' => 'Cámara sin alimentación eléctrica',
                'fecha' => now()->subDays(5),
                'estado' => 'en_proceso',
                'prioridad' => 'alta',
                'infraestructura_id' => 4,
                'tecnico_id' => $tecnico->id,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Daño Estructural',
                'ubicacion' => 'Plaza Central',
                'descripcion' => 'Poste inclinado con riesgo de caída',
                'fecha' => now()->subDays(6),
                'estado' => 'resuelto',
                'prioridad' => 'critica',
                'infraestructura_id' => 2,
                'tecnico_id' => $tecnico->id,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Falla de Sistema',
                'ubicacion' => 'Av. Libertador con Calle 5',
                'descripcion' => 'Sistema de control no responde',
                'fecha' => now()->subDays(7),
                'estado' => 'pendiente',
                'prioridad' => 'media',
                'infraestructura_id' => 1,
                'tecnico_id' => null,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Vandalismo',
                'ubicacion' => 'Terminal de Transporte',
                'descripcion' => 'Candados forzados en tres bicicletas',
                'fecha' => now()->subDays(8),
                'estado' => 'en_proceso',
                'prioridad' => 'alta',
                'infraestructura_id' => 5,
                'tecnico_id' => $tecnico->id,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Mantenimiento Preventivo',
                'ubicacion' => 'Parque Municipal',
                'descripcion' => 'Limpieza programada de filtros',
                'fecha' => now()->subDays(9),
                'estado' => 'resuelto',
                'prioridad' => 'baja',
                'infraestructura_id' => 3,
                'tecnico_id' => $tecnico->id,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'tipo' => 'Falla de Conexión',
                'ubicacion' => 'Esquina Av. Principal',
                'descripcion' => 'Cámara sin conexión a la red de monitoreo',
                'fecha' => now()->subDays(10),
                'estado' => 'pendiente',
                'prioridad' => 'media',
                'infraestructura_id' => 4,
                'tecnico_id' => null,
                'ciudadano_id' => $ciudadano->id,
            ],
        ];

        foreach ($incidencias as $incidencia) {
            Incidencia::create($incidencia);
        }
    }
}
