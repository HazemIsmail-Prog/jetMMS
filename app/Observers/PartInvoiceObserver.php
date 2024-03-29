<?php

namespace App\Observers;

use App\Models\PartInvoice;
use App\Services\CreatePartInvoiceVoucher;

class PartInvoiceObserver
{
    /**
     * Handle the PartInvoice "created" event.
     */
    public function created(PartInvoice $partInvoice): void
    {
        CreatePartInvoiceVoucher::createVoucher($partInvoice);
    }

    /**
     * Handle the PartInvoice "updated" event.
     */
    public function updated(PartInvoice $partInvoice): void
    {
        CreatePartInvoiceVoucher::editVoucher($partInvoice, $partInvoice->voucher);
    }

    /**
     * Handle the PartInvoice "deleted" event.
     */
    public function deleted(PartInvoice $partInvoice): void
    {
        $partInvoice->voucher->delete();
    }

    /**
     * Handle the PartInvoice "restored" event.
     */
    public function restored(PartInvoice $partInvoice): void
    {
        //
    }

    /**
     * Handle the PartInvoice "force deleted" event.
     */
    public function forceDeleted(PartInvoice $partInvoice): void
    {
        //
    }
}
