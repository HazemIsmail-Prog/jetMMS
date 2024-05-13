<?php

namespace App\Livewire\OperationsReports;

use App\Models\Invoice;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ExpectedInvoicesDeletion extends Component
{

    use WithPagination;

    #[Computed()]
    public function invoices()
    {
        return Invoice::query()
            ->whereDoesntHave('payments')
            ->withWhereHas('order', function ($query) {
                $query->has('invoices', '>', 1); // Ensures the order has more than one invoice
            })
            ->latest()
            ->paginate()
            // 
        ;
    }

    public function render()
    {
        // dd($this->invoices);
        return view('livewire.operations-reports.expected-invoices-deletion')->title( __('messages.expected_invoices_deletion'));
    }
}
