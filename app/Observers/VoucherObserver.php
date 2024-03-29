<?php

namespace App\Observers;

use App\Models\Voucher;

class VoucherObserver
{
    /**
     * Handle the Voucher "created" event.
     */
    public function created(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "updated" event.
     */
    public function updated(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "deleted" event.
     */
    public function deleted(Voucher $voucher): void
    {
        $voucher->voucherDetails()->delete();
    }

    /**
     * Handle the Voucher "restored" event.
     */
    public function restored(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "force deleted" event.
     */
    public function forceDeleted(Voucher $voucher): void
    {
        //
    }
}
