<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{

    public function before(User $user, string $ability): bool|null
    {
        if ($user->id === 1) {
            return true;
        }

        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('payments_create');
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->hasPermission('payments_delete');
    }

    public function collect(User $user, Payment $payment): bool
    {
        return $user->hasPermission('payments_collect');
    }

    public function mass_collect(User $user): bool
    {
        return $user->hasPermission('payments_collect');
    }

    public function uncollect(User $user, Payment $payment): bool
    {
        return $user->hasPermission('payments_uncollect');
    }

    public function change_date(User $user, Payment $payment): bool
    {
        return $user->hasPermission('payments_date_change');
    }

}
