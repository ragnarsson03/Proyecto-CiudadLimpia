<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentHistory extends Model
{
    protected $table = 'incident_history';

    protected $fillable = [
        'incident_id',
        'user_id',
        'status_id',
        'comment'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}