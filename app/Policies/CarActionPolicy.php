<?php

namespace App\Policies;

use App\Models\CarAction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarActionPolicy
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
        return $user->hasPermission('car_actions_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('car_actions_create');
    }
    
    public function update(User $user, CarAction $carAction): bool
    {
        return $user->hasPermission('car_actions_edit');
    }
    
    public function delete(User $user, CarAction $carAction): bool
    {
        return $user->hasPermission('car_actions_delete');
    }
    
}
