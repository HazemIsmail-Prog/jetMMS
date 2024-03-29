<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
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
        return $user->hasPermission('suppliers_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('suppliers_create');
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('suppliers_edit');
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('suppliers_delete');
    }

}
