<?php

namespace App\Services;

use App\Models\User;
use App\Models\Incidencia;
use App\Models\OrdenTrabajo;
use App\Models\MantenimientoPreventivo;
use App\Events\IncidenciaCreada;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionIncidencia;
use App\Mail\NotificacionMantenimiento;

class NotificationService
{
    public function notificarNuevaIncidencia(Incidencia $incidencia)
    {
        // Broadcast del evento
        broadcast(new IncidenciaCreada($incidencia))->toOthers();

        // Notificar a supervisores por email
        $supervisores = User::where('role', 'supervisor')->get();
        
        foreach ($supervisores as $supervisor) {
            Mail::to($supervisor->email)->queue(new NotificacionIncidencia($incidencia));
        }

        // Notificar al técnico asignado si existe
        if ($incidencia->tecnico_id) {
            $this->notificarTecnico($incidencia);
        }
    }

    public function notificarMantenimientoProximo(MantenimientoPreventivo $mantenimiento)
    {
        // Notificar a supervisores
        $supervisores = User::where('role', 'supervisor')->get();
        
        foreach ($supervisores as $supervisor) {
            Mail::to($supervisor->email)->queue(new NotificacionMantenimiento($mantenimiento));
        }

        // Registrar en el log
        \Log::info("Mantenimiento preventivo próximo", [
            'id' => $mantenimiento->id,
            'infraestructura' => $mantenimiento->infraestructura->tipo,
            'fecha' => $mantenimiento->proxima_ejecucion
        ]);
    }

    public function notificarTecnico(Incidencia $incidencia)
    {
        if (!$incidencia->tecnico) return;

        // Enviar email al técnico
        Mail::to($incidencia->tecnico->email)->queue(new NotificacionIncidencia($incidencia));

        // Registrar en el log
        \Log::info("Técnico notificado de nueva incidencia", [
            'tecnico_id' => $incidencia->tecnico_id,
            'incidencia_id' => $incidencia->id
        ]);
    }

    public function notificarActualizacionOrdenTrabajo(OrdenTrabajo $ordenTrabajo)
    {
        // Notificar al supervisor
        $supervisores = User::where('role', 'supervisor')->get();
        
        foreach ($supervisores as $supervisor) {
            Mail::to($supervisor->email)->queue(new NotificacionOrdenTrabajo($ordenTrabajo));
        }

        // Notificar al técnico asignado
        if ($ordenTrabajo->tecnico) {
            Mail::to($ordenTrabajo->tecnico->email)->queue(new NotificacionOrdenTrabajo($ordenTrabajo));
        }

        // Registrar en el log
        \Log::info("Orden de trabajo actualizada", [
            'orden_id' => $ordenTrabajo->id,
            'estado' => $ordenTrabajo->estado
        ]);
    }

    public function notificarAlertaInventario($material)
    {
        $supervisores = User::where('role', 'supervisor')->get();
        
        foreach ($supervisores as $supervisor) {
            Mail::to($supervisor->email)->queue(new NotificacionInventario($material));
        }

        // Registrar en el log
        \Log::info("Alerta de inventario bajo", [
            'material_id' => $material->id,
            'cantidad_actual' => $material->cantidad_disponible,
            'stock_minimo' => $material->stock_minimo
        ]);
    }
}
