<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeavePolicy
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
        return $user->hasPermission('leaves_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('leaves_create');
    }

    public function update(User $user, Leave $leave): bool
    {
        return $user->hasPermission('leaves_edit');
    }

    public function delete(User $user, Leave $leave): bool
    {
        return $user->hasPermission('leaves_delete');
    }

    public function viewAnyAttachment(User $user, Leave $leave): bool
    {
        return $user->hasPermission('leaves_attachment');
    }
}
