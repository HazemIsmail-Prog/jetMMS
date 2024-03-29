<?php

namespace App\Policies;

use App\Models\Area;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AreaPolicy
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
        return $user->hasPermission('areas_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('areas_create');
    }

    public function update(User $user, Area $area): bool
    {
        return $user->hasPermission('areas_edit');
    }

    public function delete(User $user, Area $area): bool
    {
        return $user->hasPermission('areas_delete');
    }

}
