<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoAnual extends Model
{
    use HasFactory;

    protected $table = 'presupuesto_anual';

    protected $fillable = [
        'año',
        'monto_total',
        'monto_ejecutado',
        'monto_comprometido'
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'monto_ejecutado' => 'decimal:2',
        'monto_comprometido' => 'decimal:2'
    ];

    public function partidas()
    {
        return $this->hasMany(PartidaPresupuestaria::class, 'presupuesto_id');
    }

    public function getMontoDisponibleAttribute()
    {
        return $this->monto_total - $this->monto_ejecutado - $this->monto_comprometido;
    }

    public function getPorcentajeEjecucionAttribute()
    {
        return ($this->monto_total > 0) 
            ? ($this->monto_ejecutado / $this->monto_total) * 100 
            : 0;
    }
}
