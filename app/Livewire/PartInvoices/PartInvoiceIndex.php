<?php

namespace App\Livewire\PartInvoices;

use App\Models\PartInvoice;
use App\Models\Supplier;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PartInvoiceIndex extends Component
{
    use WithPagination;

    public $filters;
    public $perPage = 10;


    public function mount()
    {
        $this->filters =
            [
                'start_date' => '',
                'end_date' => '',
                'technician_id' => [],
                'supplier_id' => [],
            ];
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function suppliers()
    {
        return Supplier::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    #[On('partInvoicesUpdated')]
    public function part_invoices()
    {
        return PartInvoice::query()
            ->with('supplier')
            ->with('contact')
            ->when($this->filters['technician_id'], function (Builder $q) {
                $q->whereIn('contact_id', $this->filters['technician_id']);
            })
            ->when($this->filters['supplier_id'], function (Builder $q) {
                $q->whereIn('supplier_id', $this->filters['supplier_id']);
            })
            ->when($this->filters['start_date'], function (Builder $q) {
                $q->whereDate('date', '>=', $this->filters['start_date']);
            })
            ->when($this->filters['end_date'], function (Builder $q) {
                $q->whereDate('date', '<=', $this->filters['end_date']);
            })
            ->latest()
            ->paginate($this->perPage);
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
