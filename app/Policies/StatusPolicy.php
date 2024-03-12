<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('statuses_menu');
    }

    public function update(User $user, Status $status): bool
    {
        return $user->hasPermission('statuses_edit');
    }

}
