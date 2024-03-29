<?php

namespace App\Policies;

use App\Models\Marketing;
use App\Models\User;

class MarketingPolicy
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
        return $user->hasPermission('marketing_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('marketing_create');
    }
    
    public function update(User $user, Marketing $marketing): bool
    {
        return $user->hasPermission('marketing_edit');
    }
    
    public function delete(User $user, Marketing $marketing): bool
    {
        return $user->hasPermission('marketing_delete');
    }

}
