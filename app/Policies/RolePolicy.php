<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
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
        return $user->hasPermission('roles_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('roles_create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('roles_edit');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermission('roles_delete');
    }

}
