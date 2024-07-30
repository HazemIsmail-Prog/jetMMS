<?php

namespace App\Policies;

use App\Models\PhoneDevice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PhoneDevicePolicy
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
        return $user->hasPermission('phone_devices_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('phone_devices_create');
    }

    public function update(User $user, PhoneDevice $phoneDevice): bool
    {
        return $user->hasPermission('phone_devices_edit');
    }

    public function delete(User $user, PhoneDevice $phoneDevice): bool
    {
        return $user->hasPermission('phone_devices_delete');
    }

    public function viewAnyAttachment(User $user, PhoneDevice $phoneDevice): bool
    {
        return $user->hasPermission('phone_devices_attachment');
    }

}
