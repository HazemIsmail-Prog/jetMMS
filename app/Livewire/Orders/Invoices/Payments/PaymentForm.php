<?php

namespace App\Livewire\Orders\Invoices\Payments;

use App\Livewire\Forms\PaymentForm as FormsPaymentForm;
use App\Models\Invoice;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentForm extends Component
{
    public Invoice $invoice;
    public $showModal = false;
    public FormsPaymentForm $form;

    #[On('showPaymentFormModal')]
    public function show(Invoice $invoice)
    {
        $this->form->reset();
        $this->invoice = $invoice;
        $this->form->setData($this->invoice);
        $this->showModal = true;

        $this->js("
        setTimeout(function() { 
            document.getElementById('amount').focus();
         }, 100);
        ");
    }

    public function updated($key, $val)
    {
        if ($key == 'form.method') {
            $this->form->knet_ref_number = null;
        }
    }

    public function save()
    {
        $this->form->updateOrCreate(); // Ovserver Applied
        $this->dispatch('paymentsUpdated');
        $this->showModal = false;

    }

    public function render()
    {
        return view('livewire.orders.invoices.payments.payment-form');
    }
}
