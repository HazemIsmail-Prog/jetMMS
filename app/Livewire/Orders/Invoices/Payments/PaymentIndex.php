<?php

namespace App\Livewire\Orders\Invoices\Payments;

use App\Events\RefreshInvoicePaymentsScreenEvent;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PaymentIndex extends Component
{
    public Invoice $invoice;

    #[Computed()]
    public function payments()
    {
        return Payment::query()
            ->where('invoice_id', $this->invoice->id)
            ->with('user')
            ->get();
    }

    public function delete(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->delete(); // Observer Applied
            $this->invoice->update(['payment_status' => $this->invoice->computePaymentStatus()]);
            DB::commit();
            $this->dispatch('paymentsUpdated');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.orders.invoices.payments.payment-index');
    }
}
