<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\User;

class Incidencia extends Model
{
    use HasFactory;

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
}
