<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidaPresupuestaria extends Model
{
    use HasFactory;

    protected $table = 'partidas_presupuestarias';

    protected $fillable = [
        'presupuesto_id',
        'nombre',
        'descripcion',
        'monto_asignado',
        'monto_ejecutado',
        'tipo_infraestructura',
        'zona_geografica'
    ];

    protected $casts = [
        'monto_asignado' => 'decimal:2',
        'monto_ejecutado' => 'decimal:2'
    ];

    public function presupuestoAnual()
    {
        return $this->belongsTo(PresupuestoAnual::class, 'presupuesto_id');
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'partida_id');
    }

    public function getMontoDisponibleAttribute()
    {
        return $this->monto_asignado - $this->monto_ejecutado;
    }

    public function getPorcentajeEjecucionAttribute()
    {
        return ($this->monto_asignado > 0) 
            ? ($this->monto_ejecutado / $this->monto_asignado) * 100 
            : 0;
    }
}
