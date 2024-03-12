<?php

namespace App\Policies;

use App\Models\CarService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('car_services_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('car_services_create');
    }

    public function update(User $user, CarService $carService): bool
    {
        return $user->hasPermission('car_services_edit');
    }

    public function delete(User $user, CarService $carService): bool
    {
        return $user->hasPermission('car_services_delete');
    }
}
