<?php

namespace App\Policies;

use App\Models\{Encuesta, User};

class EncuestaPolicy
{
    /* Helpers idénticos --------------------------------------------------------- */
    protected function isSuper(User $u): bool
    {
        return $u->hasRole('superadmin');
    }

    protected function isAdminSameCompany(User $u, ?Encuesta $e = null): bool
    {
        return $u->hasRole('admin') &&
               ($e ? $u->empresa_id === $e->empresa_id : true);
    }

    protected function isColabSameCompany(User $u, ?Encuesta $e = null): bool
    {
        return $u->hasRole('colaborador') &&
               ($e ? $u->empresa_id === $e->empresa_id : true);
    }

    /* CRUD ---------------------------------------------------------------------- */

    public function viewAny(User $u): bool
    {
        return $this->isSuper($u) || $u->hasAnyRole(['admin', 'colaborador']);
    }

    public function view(User $u, Encuesta $e): bool
    {
        return $this->isSuper($u)
            || $this->isAdminSameCompany($u, $e)
            || $this->isColabSameCompany($u, $e);
    }

    public function create(User $u): bool
    {
        return $this->isSuper($u) || $this->isAdminSameCompany($u);
    }

    public function update(User $u, Encuesta $e): bool
    {
        return $this->isSuper($u) || $this->isAdminSameCompany($u, $e);
    }

    public function delete(User $u, Encuesta $e): bool
    {
        return $this->update($u, $e);
    }

    /* Acción personalizada ------------------------------------------------------ */

    /** Responder encuesta (colaboradores) */
    public function respond(User $u, Encuesta $e): bool
    {
        return $this->isColabSameCompany($u, $e) && $e->activa;
    }
}
