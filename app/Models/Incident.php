<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use SoftDeletes;

    protected $table = 'incidents';

    protected $fillable = [
        'title',
        'description',
        'infrastructure_id',
        'status_id',
        'priority',
        'latitude',
        'longitude',
        'user_id',
        'assigned_to'
    ];

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(IncidentHistory::class);
    }

    public function photos()
    {
        return $this->hasMany(IncidentPhoto::class);
    }

    public function getPriorityColorAttribute()
    {
        return [
            'Alta' => 'danger',
            'Media' => 'warning',
            'Baja' => 'info'
        ][$this->priority] ?? 'secondary';
    }
}