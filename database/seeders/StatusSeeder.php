<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Pendiente', 'color' => 'warning'],
            ['name' => 'En Proceso', 'color' => 'info'],
            ['name' => 'Resuelto', 'color' => 'success'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}