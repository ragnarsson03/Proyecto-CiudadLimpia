<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function setPasswordAttribute($value)
    {
        if ($value && !Hash::check($value, $this->password)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    

    public const ROLES = [
        'admin' => 'Administrador',
        'supervisor' => 'Supervisor',
        'tecnico' => 'Técnico',
        'ciudadano' => 'Ciudadano'
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    public function incidenciasAsignadas()
    {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }

    public function incidenciasReportadas()
    {
        return $this->hasMany(Incidencia::class, 'ciudadano_id');
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }
}
