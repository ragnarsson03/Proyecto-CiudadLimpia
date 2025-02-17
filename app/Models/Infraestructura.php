<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Incidencia;

class Infraestructura extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'ubicacion',
        'descripcion',
        'estado',
        'ultima_revision',
        'historial_mantenimiento'
    ];

    protected $casts = [
        'ultima_revision' => 'datetime',
        'historial_mantenimiento' => 'array'
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}
