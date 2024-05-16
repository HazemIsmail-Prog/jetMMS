<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{

    // public function before(User $user, string $ability): bool|null
    // {
    //     if ($user->id === 1) {
    //         return true;
    //     }

    //     return null;
    // }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('companies_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('companies_create');
    }

    public function update(User $user, Company $company): bool
    {
        // return $user->hasPermission('companies_edit');
        return false;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->hasPermission('companies_delete');
    }

    public function viewAnyAttachment(User $user, Company $company): bool
    {
        // return $user->hasPermission('companies_attachment');
        return true;
    }

}
