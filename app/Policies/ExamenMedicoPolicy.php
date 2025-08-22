<?php
// app/Policies/ExamenMedicoPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\ExamenMedico;

class ExamenMedicoPolicy
{
    public function view(User $user, ExamenMedico $exam)
    {
        return $user->hasRole('admin')
            || ($user->hasRole('colaborador') && $exam->user_id === $user->id);
    }
    public function update(User $user, ExamenMedico $exam)
    {
        return $user->hasRole('admin');
    }
    public function delete(User $user, ExamenMedico $exam)
    {
        return $user->hasRole('admin');
    }
    public function viewAny(User $user)
    {
        return $user->hasRole('admin')
            || $user->hasRole('colaborador');
    }
}
