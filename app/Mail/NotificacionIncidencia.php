<?php

namespace App\Mail;

use App\Models\Incidencia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionIncidencia extends Mailable
{
    use Queueable, SerializesModels;

    public $incidencia;

    public function __construct(Incidencia $incidencia)
    {
        $this->incidencia = $incidencia;
    }

    public function build()
    {
        return $this->markdown('emails.incidencias.nueva')
                    ->subject('Nueva Incidencia Reportada - ' . $this->incidencia->tipo)
                    ->with([
                        'incidencia' => $this->incidencia,
                        'infraestructura' => $this->incidencia->infraestructura,
                        'ciudadano' => $this->incidencia->ciudadano
                    ]);
    }
}
