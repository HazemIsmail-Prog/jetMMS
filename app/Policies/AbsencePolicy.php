<?php

namespace App\Policies;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AbsencePolicy
{

    public function before(User $user, string $ability): bool|null
    {
        if ($user->id === 1) {
            return true;
        }

        return null;
    }
    
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('absences_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('absences_create');
    }
    
    public function update(User $user, Absence $absence): bool
    {
        return $user->hasPermission('absences_edit');
    }

    public function delete(User $user, Absence $absence): bool
    {
        return $user->hasPermission('absences_delete');
    }

    public function viewAnyAttachment(User $user, Absence $absence): bool
    {
        return $user->hasPermission('absences_attachment');
    }

}
