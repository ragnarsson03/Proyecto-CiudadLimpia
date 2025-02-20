<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use SoftDeletes;

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

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class, 'infraestructura_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function citizen()
    {
        return $this->belongsTo(User::class, 'ciudadano_id');
    }
} 