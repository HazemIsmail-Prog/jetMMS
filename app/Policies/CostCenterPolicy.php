<?php

namespace App\Policies;

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CostCenterPolicy
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
        return $user->hasPermission('const_centers_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('const_centers_create');
    }

    public function update(User $user, CostCenter $costCenter): bool
    {
        return $user->hasPermission('const_centers_edit');
    }

    public function delete(User $user, CostCenter $costCenter): bool
    {
        return $user->hasPermission('const_centers_delete');
    }

}
