<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MantenimientoPreventivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mantenimiento_preventivo';

    protected $fillable = [
        'infraestructura_id',
        'nombre',
        'descripcion',
        'frecuencia',
        'dias_frecuencia',
        'ultima_ejecucion',
        'proxima_ejecucion',
        'checklist',
        'costo_estimado',
        'duracion_estimada',
        'materiales_requeridos',
        'personal_requerido',
        'activo'
    ];

    protected $casts = [
        'ultima_ejecucion' => 'date',
        'proxima_ejecucion' => 'date',
        'checklist' => 'array',
        'materiales_requeridos' => 'array',
        'personal_requerido' => 'array',
        'costo_estimado' => 'decimal:2',
        'activo' => 'boolean'
    ];

    public function infraestructura()
    {
        return $this->belongsTo(Infraestructura::class);
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'material_mantenimiento')
            ->withPivot(['cantidad_requerida'])
            ->withTimestamps();
    }

    public function ordenesGeneradas()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }

    public function requiereEjecucion()
    {
        return $this->activo && $this->proxima_ejecucion <= now();
    }

    public function actualizarProximaEjecucion()
    {
        $this->ultima_ejecucion = now();
        $this->proxima_ejecucion = now()->addDays($this->dias_frecuencia);
        $this->save();
    }

    public function generarOrdenTrabajo()
    {
        if (!$this->requiereEjecucion()) {
            return null;
        }

        return OrdenTrabajo::create([
            'codigo' => 'MP-' . now()->format('Ymd') . '-' . $this->id,
            'infraestructura_id' => $this->infraestructura_id,
            'tipo' => 'preventivo',
            'estado' => 'pendiente',
            'prioridad' => 'media',
            'descripcion' => $this->descripcion,
            'fecha_programada' => $this->proxima_ejecucion,
            'costo_estimado' => $this->costo_estimado,
            'materiales_requeridos' => $this->materiales_requeridos,
            'personal_asignado' => $this->personal_requerido
        ]);
    }
}
