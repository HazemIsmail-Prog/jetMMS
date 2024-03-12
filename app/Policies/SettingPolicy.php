<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SettingPolicy
{
    
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('settings_menu');
    }

}
