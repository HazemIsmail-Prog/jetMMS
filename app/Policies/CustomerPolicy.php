<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
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
        return $user->hasPermission('customers_menu');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('customers_create');
    }
    
    public function update(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers_edit');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers_delete');
    }

    public function restore(User $user, Customer $customer): bool
    {
        return true;
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        return true;
    }
}
