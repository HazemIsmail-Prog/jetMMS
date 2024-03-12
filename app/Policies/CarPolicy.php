<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('cars_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('cars_create');
    }
    
    public function update(User $user, Car $car): bool
    {
        return $user->hasPermission('cars_edit');
    }
    
    public function delete(User $user, Car $car): bool
    {
        return $user->hasPermission('cars_delete');
    }

    public function viewAnyAttachment(User $user, Car $car): bool
    {
        return $user->hasPermission('cars_attachment');
    }

}
