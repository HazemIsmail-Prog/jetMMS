<?php

namespace App\Observers;

use App\Models\Payment;
use App\Events\RefreshInvoicePaymentsScreenEvent;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        RefreshInvoicePaymentsScreenEvent::dispatch($payment->invoice->id);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        RefreshInvoicePaymentsScreenEvent::dispatch($payment->invoice->id);
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
