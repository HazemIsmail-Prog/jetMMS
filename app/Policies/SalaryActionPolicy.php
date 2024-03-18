<?php

namespace App\Policies;

use App\Models\SalaryAction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalaryActionPolicy
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
        return $user->hasPermission('salary_actions_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('salary_actions_create');
    }

    public function update(User $user, SalaryAction $salary_action): bool
    {
        return $user->hasPermission('salary_actions_edit');
    }

    public function delete(User $user, SalaryAction $salary_action): bool
    {
        return $user->hasPermission('salary_actions_delete');
    }

    public function viewAnyAttachment(User $user, SalaryAction $salary_action): bool
    {
        return $user->hasPermission('salary_actions_attachment');
    }
}
