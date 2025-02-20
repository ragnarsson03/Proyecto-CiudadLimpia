<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;

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
        'desglose' => 'array'
    ];

    public static function getCurrentMonthBalance()
    {
        $currentBudget = self::where('año', now()->year)
            ->where('mes', now()->month)
            ->first();

        return $currentBudget ? number_format($currentBudget->monto_asignado - $currentBudget->monto_ejecutado, 2) : '0.00';
    }
} 