<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infrastructure extends Model
{
    use SoftDeletes;

    protected $table = 'infrastructures';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'latitude',
        'longitude'
    ];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}