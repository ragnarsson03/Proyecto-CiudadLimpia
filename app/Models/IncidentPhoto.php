<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentPhoto extends Model
{
    protected $table = 'incident_photos';

    protected $fillable = [
        'incident_id',
        'path'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}