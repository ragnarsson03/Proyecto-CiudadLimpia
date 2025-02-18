<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'personal';

    protected $fillable = [
        'user_id',
        'especialidad',
        'disponibilidad',
        'habilidades',
        'telefono',
        'direccion',
        'notas'
    ];

    protected $casts = [
        'habilidades' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ordenesAsignadas()
    {
        return $this->belongsToMany(OrdenTrabajo::class, 'personal_orden_trabajo')
            ->withPivot(['rol', 'horas_asignadas'])
            ->withTimestamps();
    }

    public function rutas()
    {
        return $this->hasMany(Ruta::class);
    }

    public function isDisponible()
    {
        return $this->disponibilidad === 'disponible';
    }

    public function tieneHabilidad($habilidad)
    {
        return in_array($habilidad, $this->habilidades ?? []);
    }
}
