<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
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
        return $user->hasPermission('departments_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('departments_create');
    }

    public function update(User $user, Department $department): bool
    {
        return $user->hasPermission('departments_edit');
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->hasPermission('departments_delete');
    }

}
