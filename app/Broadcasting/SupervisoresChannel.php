<?php

namespace App\Broadcasting;

use App\Models\User;

class SupervisoresChannel
{
    public function join(User $user)
    {
        return $user->role === 'supervisor';
    }
}
