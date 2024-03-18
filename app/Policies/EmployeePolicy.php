<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeePolicy
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
        return $user->hasPermission('employees_menu');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->hasPermission('employees_view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('employees_create');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->hasPermission('employees_edit');
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasPermission('employees_delete');
    }

    public function viewAnyAttachment(User $user, Employee $employee): bool
    {
        return $user->hasPermission('employees_attachment');
    }

}
