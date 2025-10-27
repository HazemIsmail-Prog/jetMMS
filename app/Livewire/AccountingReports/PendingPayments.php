<?php

namespace App\Livewire\AccountingReports;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Status;
use App\Models\Title;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PendingPayments extends Component
{
    use WithPagination;

    public $filters;
    public $perPage = 10;

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
                'start_created_at' => null,
                'end_created_at' => null,
                'department_id' => [],
                'technician_id' => [],
            ];
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', true)
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
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
    public function invoices()
    {
        return Invoice::query()

            ->whereRelation('order', 'status_id', Status::COMPLETED)

            ->withCount(['order as order_number' => function ($q) {
                $q->select('id');
            }])

            ->withCount(['order as technician_name' => function ($q) {
                $q->join('users', 'users.id', '=', 'orders.technician_id');
                $q->select('users.name_' . app()->getLocale());
            }])

            ->withCount(['order as department_name' => function ($q) {
                $q->join('departments', 'departments.id', '=', 'orders.department_id');
                $q->select('departments.name_' . app()->getLocale());
            }])

            ->withSum(['invoice_details as queryServicesAmount' => function ($q) {
                $q->whereRelation('service', 'type', 'service');
            }], DB::raw('quantity * price'))

            ->withSum(['invoice_details as queryPartsAmountFromDetails' => function ($q) {
                $q->whereRelation('service', 'type', 'part');
            }], DB::raw('quantity * price'))

            ->withSum('invoice_part_details as queryPartsAmountFromParts', DB::raw('quantity * price'))

            ->withSum('payments as queryPaymentsAmount', 'amount')

            ->withSum('reconciliations as queryReconciliationsAmount', 'amount')

            ->when($this->filters['technician_id'], function ($q) {
                $q->whereHas('order', function ($q) {
                    $q->whereIn('technician_id', $this->filters['technician_id']);
                });
            })

            ->when($this->filters['department_id'], function ($q) {
                $q->whereHas('order', function ($q) {
                    $q->whereIn('department_id', $this->filters['department_id']);
                });
            })

            ->whereIn('payment_status', ['pending', 'partially_paid'])

            ->when($this->filters['start_created_at'], function ($q) {
                $q->whereDate('created_at', '>=', $this->filters['start_created_at']);
            })

            ->when($this->filters['end_created_at'], function ($q) {
                $q->whereDate('created_at', '<=', $this->filters['end_created_at']);
            })

            ->orderBy('id', 'desc')

            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.accounting-reports.pending-payments');
    }
}
