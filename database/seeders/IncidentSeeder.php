<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\User;
use App\Models\Infrastructure;
use App\Models\Status;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    public function run()
    {
        $user = User::role('Ciudadano')->first();
        $tech = User::role('Técnico')->first();
        $infrastructure = Infrastructure::first();
        $pendingStatus = Status::where('name', 'Pendiente')->first();
        $inProgressStatus = Status::where('name', 'En Proceso')->first();
        $resolvedStatus = Status::where('name', 'Resuelto')->first();

        $incidents = [
            [
                'title' => 'Daño en iluminación',
                'description' => 'Farolas sin funcionar en la zona norte',
                'infrastructure_id' => $infrastructure->id,
                'status_id' => $pendingStatus->id,
                'priority' => 'Alta',
                'latitude' => 4.6580,
                'longitude' => -74.0935,
                'user_id' => $user->id,
                'assigned_to' => $tech->id
            ],
            [
                'title' => 'Acumulación de basura',
                'description' => 'Basura acumulada en esquina sur',
                'infrastructure_id' => $infrastructure->id,
                'status_id' => $inProgressStatus->id,
                'priority' => 'Media',
                'latitude' => 4.5981,
                'longitude' => -74.0758,
                'user_id' => $user->id,
                'assigned_to' => $tech->id
            ],
            [
                'title' => 'Bache en la vía',
                'description' => 'Bache grande en la calle principal',
                'infrastructure_id' => $infrastructure->id,
                'status_id' => $resolvedStatus->id,
                'priority' => 'Baja',
                'latitude' => 4.6097,
                'longitude' => -74.0817,
                'user_id' => $user->id,
                'assigned_to' => $tech->id
            ]
        ];

        foreach ($incidents as $incident) {
            Incident::create($incident);
        }
    }
}