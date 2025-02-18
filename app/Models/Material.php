<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'materiales';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad_disponible',
        'costo_unitario',
        'unidad_medida',
        'stock_minimo',
        'stock_maximo',
        'ubicacion_almacen',
        'codigo_interno',
        'proveedores'
    ];

    protected $casts = [
        'proveedores' => 'array',
        'costo_unitario' => 'decimal:2'
    ];

    public function ordenesTrabajoAsignadas()
    {
        return $this->belongsToMany(OrdenTrabajo::class, 'material_orden_trabajo')
            ->withPivot(['cantidad', 'costo_total'])
            ->withTimestamps();
    }

    public function mantenimientosPreventivos()
    {
        return $this->belongsToMany(MantenimientoPreventivo::class, 'material_mantenimiento')
            ->withPivot(['cantidad_requerida'])
            ->withTimestamps();
    }

    public function necesitaReposicion()
    {
        return $this->cantidad_disponible <= $this->stock_minimo;
    }

    public function valorTotal()
    {
        return $this->cantidad_disponible * $this->costo_unitario;
    }
}
