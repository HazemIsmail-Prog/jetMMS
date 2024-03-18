<?php

namespace App\Policies;

use App\Models\PartInvoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PartInvoicePolicy
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
        return $user->hasPermission('part_invoices_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('part_invoices_create');
    }

    public function update(User $user, PartInvoice $partInvoice): bool
    {
        return $user->hasPermission('part_invoices_edit');
    }

    public function delete(User $user, PartInvoice $partInvoice): bool
    {
        return $user->hasPermission('part_invoices_delete');
    }

}
