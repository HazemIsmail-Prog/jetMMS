<?php

namespace App\Policies;

use App\Models\CompanyBudget;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyBudgetPolicy
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
        return  $user->hasPermission('company_budgets_menu');
    }

    public function create(User $user): bool
    {
        return  $user->hasPermission('company_budgets_create');
    }

    public function update(User $user, CompanyBudget $companyBudget): bool
    {
        return  $user->hasPermission('company_budgets_edit');
    }

    public function delete(User $user, CompanyBudget $companyBudget): bool
    {
        return  $user->hasPermission('company_budgets_delete');
    }

    public function viewAnyAttachment(User $user, CompanyBudget $companyBudget): bool
    {
        return $user->hasPermission('company_budgets_attachment');
    }
}
