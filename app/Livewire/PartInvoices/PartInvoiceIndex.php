<?php

namespace App\Livewire\PartInvoices;

use App\Models\PartInvoice;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PartInvoiceIndex extends Component
{
    use WithPagination;

    #[Computed()]
    #[On('partInvoicesUpdated')]
    public function part_invoices()
    {
        return PartInvoice::query()
            ->with('supplier')
            ->with('contact')
            ->paginate(15);
    }

    public function delete(PartInvoice $partInvoice)
    {
        DB::beginTransaction();
        try {
            $partInvoice->delete();
            DB::commit();
            $this->dispatch('partInvoicesUpdated');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.part-invoices.part-invoice-index')->title(__('messages.part_invoices'));
    }
}
