<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractPolicy
{

    public function before(User $user, string $ability): bool|null
    {
        if ($user->id === 1) {
            return true;
        }

        return null;
    }

    public function viewConstructionContracts(User $user): bool
    {
        return $user->hasPermission('construction_contracts_menu');
    }

    public function viewSubscriptionContracts(User $user): bool
    {
        return $user->hasPermission('subscription_contracts_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('contracts_create');
    }

    public function update(User $user, Contract $contract): bool
    {
        return $user->hasPermission('contracts_edit') || $contract->user_id == $user->id;
    }

    public function delete(User $user, Contract $contract): bool
    {
        return $user->hasPermission('contracts_delete');
    }

    public function viewAnyAttachment(User $user, Contract $contract): bool
    {
        return $user->hasPermission('contracts_attachment');
    }
}
