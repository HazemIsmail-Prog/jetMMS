<?php

namespace App\Livewire\PartInvoices;

use App\Exports\PartInvoiceExport;
use App\Models\Department;
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
use Maatwebsite\Excel\Facades\Excel;


class PartInvoiceIndex extends Component
{
    use WithPagination;

    public $filters;
    public $department_id = [];
    public $perPage = 10;
    public int $maxExportSize = 5000;

    public function excel()
    {
        if ($this->getData()->count() > $this->maxExportSize) {
            return;
        } else {
            return Excel::download(new PartInvoiceExport('livewire.part-invoices.excel.excel', 'Part Invoices', $this->getData()->get()), 'Part Invoices.xlsx');  //Excel
        }
    }


    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->filters =
            [
                'search' => '',
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
    public function departments()
    {
        return Department::query()
            ->where('is_service', 1)
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }


    #[Computed()]
    #[On('partInvoicesUpdated')]
    public function getData()
    {
        return PartInvoice::query()
            ->with('supplier')
            ->with('contact')
            ->when($this->filters['search'], function (Builder $q) {
                $q->where('notes', 'like', '%' . $this->filters['search'] . '%');
            })
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
            ->latest();
    }
    #[Computed()]
    public function part_invoices()
    {
        return $this->getData->paginate($this->perPage);
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

    public function updatedDepartmentId($val)
    {
        $this->filters['technician_id'] = User::whereIn('department_id', $this->department_id)->pluck('id');
    }

    public function render()
    {
        return view('livewire.part-invoices.part-invoice-index')->title(__('messages.part_invoices'));
    }
}
