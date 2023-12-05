<?php

namespace App\Livewire\Orders;

use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceCard extends Component
{
    public Invoice $invoice;

    #[Computed]
    #[On('paymentReceived')]
    public function payments() {
        return Payment::query()
        ->where('invoice_id',$this->invoice->id)
        ->with('user')
        ->get()
        ;
    }

    public function deletePayment(Payment $payment) {
        $payment->delete();
    }
    public function deleteInvoice(Invoice $invoice) {
        $invoice->payments()->delete();
        $invoice->delete();
        $this->dispatch('invoiceDeleted');
    }

    public function render()
    {
        return view('livewire.orders.invoice-card');
    }
}
