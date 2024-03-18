<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPolicy
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
        return $user->hasPermission('statuses_menu');
    }

    public function update(User $user, Status $status): bool
    {
        return $user->hasPermission('statuses_edit');
    }

}
