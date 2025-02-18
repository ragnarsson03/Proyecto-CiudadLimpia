<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Infraestructura;
use App\Models\User;

class Incidencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incidencias';

    protected $fillable = [
        'tipo',
        'ubicacion',
        'descripcion',
        'fecha',
        'estado',
        'prioridad',
        'latitud',
        'longitud',
        'infraestructura_id',
        'tecnico_id',
        'ciudadano_id'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8'
    ];

    public const ESTADOS = [
        'pendiente',
        'en_proceso',
        'resuelto',
        'cancelado'
    ];

    public const PRIORIDADES = [
        'baja',
        'media',
        'alta'
    ];

    public function infraestructura()
    {
        return $this->belongsTo(Infraestructura::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function ciudadano()
    {
        return $this->belongsTo(User::class, 'ciudadano_id');
    }

    public function ordenTrabajo()
    {
        return $this->hasOne(OrdenTrabajo::class);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    public function scopeResueltas($query)
    {
        return $query->where('estado', 'resuelto');
    }

    public function scopePrioridadAlta($query)
    {
        return $query->where('prioridad', 'alta');
    }

    public function getEstadoColorAttribute()
    {
        return [
            'pendiente' => 'yellow',
            'en_proceso' => 'blue',
            'resuelto' => 'green',
            'cancelado' => 'red'
        ][$this->estado] ?? 'gray';
    }

    public function getPrioridadColorAttribute()
    {
        return [
            'baja' => 'green',
            'media' => 'yellow',
            'alta' => 'red'
        ][$this->prioridad] ?? 'gray';
    }
}
