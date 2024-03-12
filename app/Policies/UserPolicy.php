<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users_create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('users_edit');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission('users_delete');
    }

}
