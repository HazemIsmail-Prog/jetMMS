<?php

namespace App\Policies;

use App\Models\CompanyContract;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyContractPolicy
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
        return $user->hasPermission('company_contracts_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('company_contracts_create');
    }

    public function update(User $user, CompanyContract $companyContract): bool
    {
        return $user->hasPermission('company_contracts_edit');
    }

    public function delete(User $user, CompanyContract $companyContract): bool
    {
        return $user->hasPermission('company_contracts_delete');
    }

    public function viewAnyAttachment(User $user, CompanyContract $companyContract): bool
    {
        return $user->hasPermission('company_contracts_attachment');
    }

}
