<?php

namespace App\Policies;

use App\Models\Incidencia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncidenciaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Incidencia $incidencia): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Incidencia $incidencia): bool
    {
        return $user->role === 'admin' || 
               $user->role === 'tecnico' || 
               ($user->role === 'ciudadano' && $incidencia->ciudadano_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Incidencia $incidencia): bool
    {
        return $user->role === 'admin';
    }
}
