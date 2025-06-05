<?php

namespace App\Policies;

use App\Models\{Capacitacion, User};

class CapacitacionPolicy
{
    /* ===== Helpers (roles y empresa) ==================================== */
    protected function isSuper(User $u): bool
    {
        return $u->hasRole('superadmin');
    }

    protected function isAdminSameCompany(User $u, ?Capacitacion $c = null): bool
    {
        return $u->hasRole('admin')
            && ($c ? $u->empresa_id === $c->empresa_id : true);
    }

    protected function isColabSameCompany(User $u, ?Capacitacion $c = null): bool
    {
        return $u->hasRole('colaborador')
            && ($c ? $u->empresa_id === $c->empresa_id : true);
    }

    /* ===== CRUD estándar =============================================== */
    public function viewAny(User $u): bool
    {
        return $this->isSuper($u) || $u->hasAnyRole(['admin', 'colaborador']);
    }

    /**
     * Regla clave:
     * – Super y admin de la empresa: siempre
     * – Colaborador de la empresa:
     *     • Cursos abiertos u obligatorios → siempre puede ver
     *     • Cursos manuales → sólo si está inscrito en pivot
     */
    public function view(User $u, Capacitacion $c): bool
    {
        if ($this->isSuper($u) || $this->isAdminSameCompany($u, $c)) {
            return true;
        }

        if ($this->isColabSameCompany($u, $c)) {
            if ($c->tipo_asignacion !== 'manual') {
                return true;  // abierta u obligatoria
            }
            // manual: debe estar en pivot
            return $c->participantes()->where('users.id', $u->id)->exists();
        }

        return false;
    }

    public function create(User $u): bool
    {
        return $this->isSuper($u) || $this->isAdminSameCompany($u);
    }

    public function update(User $u, Capacitacion $c): bool
    {
        return $this->isSuper($u) || $this->isAdminSameCompany($u, $c);
    }

    public function delete(User $u, Capacitacion $c): bool
    {
        return $this->update($u, $c);  // misma regla
    }

    /* ===== Acciones personalizadas ===================================== */

    /** Admin asigna o des-asigna colaboradores */
    public function asignar(User $u, Capacitacion $c): bool
    {
        return $this->isAdminSameCompany($u, $c);
    }

    /** Colaborador se inscribe en un curso abierto */
    public function inscribirse(User $u, Capacitacion $c): bool
    {
        return $c->tipo_asignacion === 'abierta'
            && $this->isColabSameCompany($u, $c)
            && ! $c->participantes()->where('users.id', $u->id)->exists();
    }
}
