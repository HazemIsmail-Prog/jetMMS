<?php

namespace App\Livewire\Orders\Invoices;

use App\Models\Invoice;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceIndex extends Component
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

    #[Computed]
    #[On('invoicesUpdated')]
    #[On('paymentsUpdated')]
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
        return view('livewire.orders.invoices.invoice-index');
    }
}
