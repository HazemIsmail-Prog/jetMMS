<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{

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
        return $user->hasPermission('companies_edit');
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->hasPermission('companies_delete');
    }

}
