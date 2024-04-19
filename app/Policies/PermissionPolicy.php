<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
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
        return $user->hasPermission('permission_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('permissions_create');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermission('permissions_edit');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermission('permissions_delete');
    }

}
