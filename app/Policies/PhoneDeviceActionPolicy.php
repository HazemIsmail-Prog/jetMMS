<?php

namespace App\Policies;

use App\Models\PhoneDeviceAction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PhoneDeviceActionPolicy
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
        return $user->hasPermission('phone_device_actions_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('phone_device_actions_create');
    }
    
    public function update(User $user, PhoneDeviceAction $phoneDeviceAction): bool
    {
        return $user->hasPermission('phone_device_actions_edit');
    }
    
    public function delete(User $user, PhoneDeviceAction $phoneDeviceAction): bool
    {
        return $user->hasPermission('phone_device_actions_delete');
    }
    
}
