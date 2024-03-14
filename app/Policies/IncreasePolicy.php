<?php

namespace App\Policies;

use App\Models\Increase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncreasePolicy
{
    
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('increases_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('increases_create');
    }

    public function update(User $user, Increase $increase): bool
    {
        return $user->hasPermission('increases_edit');
    }

    public function delete(User $user, Increase $increase): bool
    {
        return $user->hasPermission('increases_delete');
    }

    public function viewAnyAttachment(User $user, Increase $increase): bool
    {
        return $user->hasPermission('increases_attachment');
    }
}
