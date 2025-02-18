<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presupuesto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'presupuestos';

    protected $fillable = [
        'año',
        'mes',
        'monto_asignado',
        'monto_ejecutado',
        'monto_comprometido',
        'categoria',
        'zona',
        'desglose',
        'notas'
    ];

    protected $casts = [
        'monto_asignado' => 'decimal:2',
        'monto_ejecutado' => 'decimal:2',
        'monto_comprometido' => 'decimal:2',
        'desglose' => 'array'
    ];

    public function montoDisponible()
    {
        return $this->monto_asignado - $this->monto_ejecutado - $this->monto_comprometido;
    }

    public function porcentajeEjecucion()
    {
        return $this->monto_asignado > 0 
            ? ($this->monto_ejecutado / $this->monto_asignado) * 100 
            : 0;
    }

    public function excedido()
    {
        return $this->monto_ejecutado > $this->monto_asignado;
    }

    public function comprometer($monto)
    {
        if ($this->montoDisponible() >= $monto) {
            $this->monto_comprometido += $monto;
            $this->save();
            return true;
        }
        return false;
    }

    public function ejecutar($monto)
    {
        $this->monto_ejecutado += $monto;
        $this->monto_comprometido = max(0, $this->monto_comprometido - $monto);
        $this->save();
    }

    public function scopeAnual($query, $año)
    {
        return $query->where('año', $año);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorZona($query, $zona)
    {
        return $query->where('zona', $zona);
    }
}
