<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
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
        return $user->hasPermission('invoices_menu');
    }

    public function viewReport(User $user): bool
    {
        return $user->hasPermission('invoices_per_technician_report');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('invoices_create');
    }
    
    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->hasPermission('invoices_delete');
    }
    
    public function discount(User $user, Invoice $invoice): bool
    {
        return $user->hasPermission('invoices_discount');
    }

    public function createPayment(User $user, Invoice $invoice): bool
    {
        return $user->hasPermission('payments_create');
    }
 
    public function restore(User $user, Invoice $invoice): bool
    {
        return true;
    }
    
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        return true;
    }
}
