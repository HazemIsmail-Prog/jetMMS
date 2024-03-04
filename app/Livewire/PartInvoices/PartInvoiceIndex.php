<?php

namespace App\Livewire\PartInvoices;

use App\Models\PartInvoice;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PartInvoiceIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('partInvoicesUpdated')]
    public function part_invoices()
    {
        return PartInvoice::query()
        ->with('supplier')
        ->with('contact')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.part-invoices.part-invoice-index');
    }
}
