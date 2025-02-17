<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Infraestructura;
use App\Models\User;

class Incidencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'ubicacion',
        'descripcion',
        'fecha',
        'estado',
        'prioridad',
        'infraestructura_id',
        'tecnico_id',
        'ciudadano_id'
    ];

    protected $casts = [
        'fecha' => 'datetime'
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
