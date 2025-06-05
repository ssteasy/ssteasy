<?php
// app/Policies/CommitteePolicy.php
namespace App\Policies;

use App\Models\Committee;
use App\Models\User;

class CommitteePolicy
{
    /* ===== VISUALIZAR ===== */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'colaborador']);
    }

    public function view(User $user, Committee $committee): bool
    {
        return $this->viewAny($user)
            && ($user->hasRole('superadmin') || $committee->empresa_id == $user->empresa_id);
    }

    /* ===== CREAR / EDITAR / BORRAR ===== */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, Committee $committee): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin'])
            && $committee->empresa_id == $user->empresa_id;
    }

    public function delete(User $user, Committee $committee): bool
    {
        return $this->update($user, $committee);
    }
}
