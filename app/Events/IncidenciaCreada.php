<?php

namespace App\Events;

use App\Models\Incidencia;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidenciaCreada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incidencia;

    public function __construct(Incidencia $incidencia)
    {
        $this->incidencia = $incidencia;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('supervisores');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->incidencia->id,
            'tipo' => $this->incidencia->tipo,
            'ubicacion' => $this->incidencia->ubicacion,
            'prioridad' => $this->incidencia->prioridad,
            'fecha' => $this->incidencia->fecha->format('Y-m-d H:i:s'),
            'infraestructura' => [
                'id' => $this->incidencia->infraestructura->id,
                'tipo' => $this->incidencia->infraestructura->tipo,
                'ubicacion' => $this->incidencia->infraestructura->ubicacion
            ]
        ];
    }
}
