<?php

namespace App\Livewire\Orders;

use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Component;

class PaymentForm extends Component
{
    public Invoice $invoice;
    public $payment = [];
    public $showForm = false;

    public function showPaymentForm()
    {
        $this->showForm = true;
    }

    public function hidePaymentForm()
    {
        $this->reset('payment', 'showForm');
    }

    public function save_payment()
    {

        // TODO:DB::transaction
        Payment::create([
            'invoice_id'=>$this->invoice->id,
            'amount'=>$this->payment['amount'],
            'method'=>$this->payment['method'],
            'user_id'=>auth()->id(),
        ]);

        $this->invoice->update(['payment_status' => $this->invoice->computePaymentStatus()]);

        $this->dispatch('paymentReceived');
    }

    public function render()
    {
        return view('livewire.orders.payment-form');
    }
}
