<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServicePolicy
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
        return $user->hasPermission('services_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('services_create');
    }
    
    public function update(User $user, Service $service): bool
    {
        return $user->hasPermission('services_edit');
    }
    
    public function delete(User $user, Service $service): bool
    {
        return $user->hasPermission('services_delete');
    }

}
