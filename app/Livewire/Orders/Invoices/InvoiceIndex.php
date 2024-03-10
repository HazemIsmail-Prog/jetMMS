<?php

namespace App\Livewire\Orders\Invoices;

use App\Models\Invoice;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceIndex extends Component
{
    public Order $order;

    #[Computed]
    #[On('invoicesUpdated')]
    #[On('paymentsUpdated')]
    #[On('echo:invoices.{order.id},RefreshOrderInvoicesScreenEvent')]
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
