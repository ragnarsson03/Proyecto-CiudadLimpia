<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Incidencia;
use App\Models\MantenimientoPreventivo;

class Infraestructura extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'infraestructuras';

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

    public const TIPOS = [
        'Parque',
        'Semáforo',
        'Contenedor',
        'Luminaria'
    ];

    public const ESTADOS = [
        'operativo',
        'mantenimiento',
        'fuera_de_servicio'
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    public function mantenimientos()
    {
        return $this->hasMany(MantenimientoPreventivo::class);
    }

    public function scopeOperativas($query)
    {
        return $query->where('estado', 'operativo');
    }

    public function scopeEnMantenimiento($query)
    {
        return $query->where('estado', 'mantenimiento');
    }

    public function scopeFueraDeServicio($query)
    {
        return $query->where('estado', 'fuera_de_servicio');
    }
}
