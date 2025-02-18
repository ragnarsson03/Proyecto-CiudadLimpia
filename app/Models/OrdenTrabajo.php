<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ordenes_trabajo';

    protected $fillable = [
        'codigo',
        'incidencia_id',
        'infraestructura_id',
        'tipo',
        'estado',
        'prioridad',
        'descripcion',
        'fecha_programada',
        'fecha_inicio',
        'fecha_fin',
        'costo_estimado',
        'costo_real',
        'materiales_requeridos',
        'personal_asignado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_programada' => 'datetime',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'materiales_requeridos' => 'array',
        'personal_asignado' => 'array',
        'costo_estimado' => 'decimal:2',
        'costo_real' => 'decimal:2'
    ];

    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class);
    }

    public function infraestructura()
    {
        return $this->belongsTo(Infraestructura::class);
    }

    public function personal()
    {
        return $this->belongsToMany(Personal::class, 'personal_orden_trabajo')
            ->withPivot(['rol', 'horas_asignadas'])
            ->withTimestamps();
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'material_orden_trabajo')
            ->withPivot(['cantidad', 'costo_total'])
            ->withTimestamps();
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function estaRetrasada()
    {
        return $this->fecha_programada < now() && $this->estado !== 'completada';
    }

    public function calcularCostoTotal()
    {
        return $this->materiales()
            ->sum(\DB::raw('material_orden_trabajo.cantidad * materiales.costo_unitario'));
    }
}
