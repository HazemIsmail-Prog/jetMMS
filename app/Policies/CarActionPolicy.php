<?php

namespace App\Policies;

use App\Models\CarAction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarActionPolicy
{

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
