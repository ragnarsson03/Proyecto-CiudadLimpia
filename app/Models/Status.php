<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    
    protected $fillable = [
        'name',
        'color',
        'description'
    ];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}