<?php

namespace App\Livewire\Orders;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Order $order;


    #[On('showInvoicesModal')]
    public function show($order_id)
    {
        $this->reset();
        $this->order = Order::find($order_id);
        $this->modalTitle = __('messages.invoices_for_order_number') . str_pad($order_id, 8, '0', STR_PAD_LEFT);
        $this->showModal = true;
    }

    #[Computed]
    #[On('invoiceCreated')]
    #[On('invoiceDeleted')]
    public function invoices()
    {
        return
            Invoice::query()
            ->where('order_id', $this->order->id)
            ->with('invoice_details.service')
            ->with('payments')
            ->get();
    }

    public function render()
    {
        return view('livewire.orders.invoice-modal');
    }
}
