<?php

namespace App\Observers;

use App\Events\RefreshDepartmentScreenEvent;
use App\Events\RefreshOrderInvoicesScreenEvent;
use App\Models\Invoice;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        broadcast(new RefreshOrderInvoicesScreenEvent($invoice->order_id))->toOthers();
        broadcast(new RefreshDepartmentScreenEvent($invoice->order->department_id, $invoice->order_id))->toOthers();

    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        if ($invoice->voucher) {
            $invoice->voucher->delete();
        }

        broadcast(new RefreshOrderInvoicesScreenEvent($invoice->order_id))->toOthers();
        broadcast(new RefreshDepartmentScreenEvent($invoice->order->department_id, $invoice->order_id))->toOthers();

    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
