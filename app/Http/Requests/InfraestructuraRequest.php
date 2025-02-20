<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InfraestructuraRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipo' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'estado' => 'required|in:operativo,mantenimiento,fuera_de_servicio',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'historial_mantenimiento' => 'nullable|array'
        ];
    }
} 