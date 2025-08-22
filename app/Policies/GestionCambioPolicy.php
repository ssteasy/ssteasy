<?php

namespace App\Policies;

use App\Models\GestionCambio;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GestionCambioPolicy
{
    public function viewAny(User $user)
{
    return $user->hasRole(['admin','superadmin']);
}

public function view(User $user, GestionCambio $cambio)
{
    return $user->hasRole('superadmin') ||
           $cambio->empresa_id === $user->empresa_id;
}

public function create(User $user)
{
    return $user->hasRole(['admin','superadmin']);
}

public function update(User $user, GestionCambio $cambio)
{
    return $this->view($user, $cambio);
}

public function delete(User $user, GestionCambio $cambio)
{
    return $this->view($user, $cambio);
}

}
