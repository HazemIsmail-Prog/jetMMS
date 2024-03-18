<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShiftPolicy
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
        return $user->hasPermission('shifts_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('shifts_create');
    }

    public function update(User $user, Shift $shift): bool
    {
        return $user->hasPermission('shifts_edit');
    }

    public function delete(User $user, Shift $shift): bool
    {
        return $user->hasPermission('shifts_delete');
    }

}
