<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class VerificarInventario extends Command
{
    protected $signature = 'inventario:verificar';
    protected $description = 'Verifica los niveles de inventario';

    public function handle(NotificationService $notificationService)
    {
        $this->info('Verificando niveles de inventario...');

        // Obtener materiales con bajo stock
        $materialesBajoStock = Material::where('cantidad_disponible', '<=', DB::raw('stock_minimo'))
            ->get();

        foreach ($materialesBajoStock as $material) {
            $this->info("Notificando bajo stock de material: {$material->nombre}");
            $notificationService->notificarAlertaInventario($material);
        }

        $this->info("Se encontraron {$materialesBajoStock->count()} materiales con bajo stock");
        return Command::SUCCESS;
    }
}
