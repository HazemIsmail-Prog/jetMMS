<?php

namespace App\Livewire\Orders\Invoices\Discount;

use App\Models\Invoice;
use Livewire\Attributes\On;
use Livewire\Component;

class DiscountForm extends Component
{
    public Invoice $invoice;
    public $showModal = false;
    public $discount;

    #[On('showDiscountFormModal')]
    public function show(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->discount = $this->invoice->discount;
        $this->showModal = true;

        $this->js("
        setTimeout(function() { 
            document.getElementById('discount').focus();
         }, 100);
        ");
    }

    public function save()
    {
        $this->invoice->discount = $this->discount;
        $this->invoice->save();
        $this->dispatch('discountUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.orders.invoices.discount.discount-form');
    }
}
