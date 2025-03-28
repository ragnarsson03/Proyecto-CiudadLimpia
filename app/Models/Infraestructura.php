<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Incidencia;
use App\Models\MantenimientoPreventivo;
use App\Models\TipoInfraestructura;
use App\Models\OrdenTrabajo;

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
        'historial_mantenimiento',
        'latitud',
        'longitud'
    ];

    protected $casts = [
        'historial_mantenimiento' => 'array',
        'ultima_revision' => 'datetime',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8'
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

    public function tipoInfraestructura()
    {
        return $this->belongsTo(TipoInfraestructura::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    public function mantenimientosPreventivos()
    {
        return $this->hasMany(MantenimientoPreventivo::class);
    }

    public function ordenesTrabajos()
    {
        return $this->hasMany(OrdenTrabajo::class);
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
