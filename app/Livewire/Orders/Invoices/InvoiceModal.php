<?php

namespace App\Livewire\Orders\Invoices;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceModal extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Order $order;

    #[On('showInvoicesModal')]
    public function show(Order $order)
    {
        $this->order = $order;
        $this->modalTitle = __('messages.invoices_for_order_number') . str_pad($this->order->id, 8, '0', STR_PAD_LEFT);
        $this->showModal = true;

    }

    public function render()
    {
        return view('livewire.orders.invoices.invoice-modal');
    }
}
