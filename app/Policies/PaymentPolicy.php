<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{

    public function create(User $user): bool
    {
        return $user->hasPermission('payments_create');
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->hasPermission('payments_delete');
    }

}
