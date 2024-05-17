<?php

namespace App\Observers;

use App\Models\Payment;
use App\Events\RefreshInvoicePaymentsScreenEvent;
use App\Models\Invoice;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $this->setPaymentStatus($payment->invoice);
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
        $this->setPaymentStatus($payment->invoice);
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

    private function setPaymentStatus(Invoice $invoice): void
    {
        $payment_status = $invoice->totalPaidAmount == 0
            ? 'pending'
            : ($invoice->remainingAmount == 0
                ? 'paid'
                : 'partially_paid'
            );
        $invoice->payment_status = $payment_status;
        $invoice->save();
    }
}
