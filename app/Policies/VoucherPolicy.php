<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Auth\Access\Response;

class VoucherPolicy
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
        return $user->hasPermission('journal_vouchers_menu');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('journal_vouchers_view');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('journal_vouchers_create');
    }

    public function update(User $user, Voucher $voucher): bool
    {
        return $user->hasPermission('journal_vouchers_edit');
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        return $user->hasPermission('journal_vouchers_delete');
    }
}
