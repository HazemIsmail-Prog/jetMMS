<?php

namespace App\Livewire\Orders\Invoices;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceCard extends Component
{
    public Invoice $invoice;

    public function delete(Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            $invoice->payments()->delete();
            $invoice->delete();
            DB::commit();
            $this->dispatch('invoicesUpdated');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    #[On('invoicesUpdated')]
    #[On('paymentsUpdated')]
    public function render()
    {
        return view('livewire.orders.invoices.invoice-card');
    }
}
