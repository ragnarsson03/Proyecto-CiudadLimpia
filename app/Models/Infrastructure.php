<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infrastructure extends Model
{
    use SoftDeletes;

    protected $table = 'infraestructuras';

    protected $fillable = [
        'tipo',
        'ubicacion',
        'descripcion',
        'estado',
        'ultima_revision',
        'historial_mantenimiento'
    ];

    protected $casts = [
        'historial_mantenimiento' => 'array',
        'ultima_revision' => 'datetime'
    ];

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'infraestructura_id');
    }
} 