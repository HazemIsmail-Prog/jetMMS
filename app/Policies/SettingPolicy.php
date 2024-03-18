<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SettingPolicy
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
        return $user->hasPermission('settings_menu');
    }

}
