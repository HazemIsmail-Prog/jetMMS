<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class AccountPolicy
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
        return $user->hasPermission('accounts_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('accounts_create');
    }
    
    public function update(User $user, Account $account): bool
    {
        return $user->hasPermission('accounts_edit');
    }
    
    public function delete(User $user, Account $account): bool
    {
        return $user->hasPermission('accounts_delete');
    }

}
