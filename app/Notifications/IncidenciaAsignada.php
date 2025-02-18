<?php

namespace App\Notifications;

use App\Models\Incidencia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncidenciaAsignada extends Notification implements ShouldQueue
{
    use Queueable;

    protected $incidencia;

    public function __construct(Incidencia $incidencia)
    {
        $this->incidencia = $incidencia;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva Incidencia Asignada')
            ->line('Se te ha asignado una nueva incidencia.')
            ->line('Infraestructura: ' . $this->incidencia->infraestructura->tipo)
            ->line('Ubicación: ' . $this->incidencia->infraestructura->ubicacion)
            ->line('Prioridad: ' . ucfirst($this->incidencia->prioridad))
            ->action('Ver Incidencia', url('/incidencia/' . $this->incidencia->id))
            ->line('Por favor, revisa los detalles y actualiza el estado cuando comiences a trabajar en ella.');
    }

    public function toArray($notifiable)
    {
        return [
            'incidencia_id' => $this->incidencia->id,
            'mensaje' => 'Nueva incidencia asignada: ' . $this->incidencia->infraestructura->tipo,
            'tipo' => 'asignacion',
            'prioridad' => $this->incidencia->prioridad
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'incidencia_id' => $this->incidencia->id,
            'mensaje' => 'Nueva incidencia asignada: ' . $this->incidencia->infraestructura->tipo,
            'tipo' => 'asignacion',
            'prioridad' => $this->incidencia->prioridad
        ]);
    }
}
