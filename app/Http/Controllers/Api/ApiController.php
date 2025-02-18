<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use App\Models\Infraestructura;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function reportarIncidencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
            'ubicacion' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'infraestructura_id' => 'required|exists:infraestructuras,id',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $incidencia = new Incidencia($request->all());
        $incidencia->estado = 'pendiente';
        $incidencia->prioridad = 'media';
        $incidencia->ciudadano_id = Auth::id();
        $incidencia->fecha = now();

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('incidencias', 'public');
            $incidencia->imagen_url = $path;
        }

        $incidencia->save();

        // Notificar a los supervisores
        $this->notificarSupervisores($incidencia);

        return response()->json([
            'message' => 'Incidencia reportada exitosamente',
            'incidencia' => $incidencia
        ], 201);
    }

    public function consultarIncidencias(Request $request)
    {
        $incidencias = Incidencia::where('ciudadano_id', Auth::id())
            ->with(['infraestructura', 'tecnico'])
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return response()->json($incidencias);
    }

    public function consultarInfraestructuras(Request $request)
    {
        $query = Infraestructura::query();

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('ubicacion')) {
            $query->where('ubicacion', 'ilike', '%' . $request->ubicacion . '%');
        }

        $infraestructuras = $query->paginate(10);

        return response()->json($infraestructuras);
    }

    public function obtenerEstadisticas()
    {
        $estadisticas = [
            'total_incidencias' => Incidencia::count(),
            'incidencias_resueltas' => Incidencia::where('estado', 'resuelto')->count(),
            'incidencias_pendientes' => Incidencia::where('estado', 'pendiente')->count(),
            'infraestructuras_operativas' => Infraestructura::where('estado', 'operativo')->count(),
            'infraestructuras_mantenimiento' => Infraestructura::where('estado', 'mantenimiento')->count()
        ];

        return response()->json($estadisticas);
    }

    private function notificarSupervisores(Incidencia $incidencia)
    {
        $supervisores = User::where('role', 'supervisor')->get();
        
        foreach ($supervisores as $supervisor) {
            // Enviar notificación push si el supervisor tiene token FCM
            if ($supervisor->fcm_token) {
                $this->enviarNotificacionPush(
                    $supervisor->fcm_token,
                    'Nueva Incidencia Reportada',
                    "Se ha reportado una nueva incidencia en {$incidencia->ubicacion}"
                );
            }
        }
    }

    private function enviarNotificacionPush($token, $titulo, $mensaje)
    {
        $data = [
            'to' => $token,
            'notification' => [
                'title' => $titulo,
                'body' => $mensaje,
                'sound' => 'default'
            ]
        ];

        $headers = [
            'Authorization: key=' . config('services.fcm.key'),
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
}
