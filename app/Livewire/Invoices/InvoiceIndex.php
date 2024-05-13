<?php

namespace App\Livewire\Invoices;

use App\Exports\InvoicesExport;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceIndex extends Component
{
    use WithPagination;

    public int $maxExportSize = 5000;
    public $filters;
    public $perPage = 10;

    public function updatedPerPage() {
        $this->resetPage();
    }

    public function updatedFilters() {
        $this->resetPage();
    }


    public function mount()
    {

        $this->filters =
            [
                'invoice_id' => '',
                'order_id' => '',
                'start_created_at' => '',
                'end_created_at' => '',
                'department_id' => [],
                'technician_id' => [],
                'customer_name' => '',
                'customer_phone' => '',
                'payment_status' => '',
                'customer_id' => '',
                'payment_status' => '',
            ];
    }

    public function getData()
    {
        return Invoice::query()
        ->select('*', DB::raw('(SELECT COUNT(*) FROM invoices AS i WHERE i.order_id = invoices.order_id) AS order_invoices_count'))
            ->orderBy('id', 'desc')
            ->with('order.department:id,name_ar,name_en')
            ->with('order.customer:id,name')
            ->with('order.phone:id,number')
            ->with('order.technician:id,name_ar,name_en')

            ->withSum('invoice_details as servicesAmountSum',DB::raw('quantity * price'))

            ->withSum(['invoice_details as invoiceDetailsPartsAmountSum' => function($q){
                $q->whereHas('service',function($q){
                    $q->where('type','part');
                });
            }],DB::raw('quantity * price'))

            ->withSum(['invoice_part_details as internalPartsAmountSum' => function($q){
                    $q->where('type','internal');
            }],DB::raw('quantity * price'))

            ->withSum(['invoice_part_details as externalPartsAmountSum' => function($q){
                    $q->where('type','external');
            }],DB::raw('quantity * price'))

            ->withSum('payments as totalPaidAmountSum','amount')

            ->withSum(['payments as totalCashAmountSum' => function($q){
                $q->where('method','cash');
            }],'amount')

            ->withSum(['payments as totalKnetAmountSum' => function($q){
                $q->where('method','knet');
            }],'amount')

            ->withCount(['payments as collectedPayments' => function ($query) {
                $query->where('is_collected', true);
            }])

            ->when($this->filters['payment_status'], function (Builder $q) {
                $q->where('payment_status', $this->filters['payment_status']);
            })
            ->when($this->filters['customer_name'], function (Builder $q) {
                $q->whereRelation('order.customer', 'name', 'like', '%' . $this->filters['customer_name'] . '%');
            })
            ->when($this->filters['customer_phone'], function (Builder $q) {
                $q->whereRelation('order.phone', 'number', 'like', $this->filters['customer_phone'] . '%');
            })
            ->when($this->filters['invoice_id'], function ($q) {
                $q->where('id', $this->filters['invoice_id']);
            })
            ->when($this->filters['order_id'], function ($q) {
                $q->where('order_id', $this->filters['order_id']);
            })
            ->when($this->filters['technician_id'], function (Builder $q) {
                $q->whereHas('order', function (Builder $q) {
                    $q->whereIn('technician_id', $this->filters['technician_id']);
                });
            })
            ->when($this->filters['department_id'], function (Builder $q) {
                $q->whereHas('order', function (Builder $q) {
                    $q->whereIn('department_id', $this->filters['department_id']);
                });
            })
            ->when($this->filters['start_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '>=', $this->filters['start_created_at']);
            })
            ->when($this->filters['end_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '<=', $this->filters['end_created_at']);
            });
    }

    #[Computed()]
    public function technicians()
    {
        return User::whereHas('orders_technician')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->whereHas('orders', function (Builder $q) {
                $q->where('status_id', Status::COMPLETED);
            })
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    #[On('invoicesUpdated')]
    #[On('paymentsUpdated')]
    public function invoices()
    {
        return $this->getData()
            ->paginate($this->perPage);
    }

    public function excel()
    {
        if ($this->getData()->count() > $this->maxExportSize) {
            return;
        } else {
            return Excel::download(new InvoicesExport('livewire.invoices.excel.excel', 'Invoices', $this->getData()->get()), 'Invoices.xlsx');  //Excel
        }
    }

    public function delete(Invoice $invoice)
    {
        $invoice->payments()->delete();
        $invoice->delete();
        $this->dispatch('invoicesUpdated');
    }

    public function dateClicked($date)
    {
        $this->filters['start_created_at'] = $date;
        $this->filters['end_created_at'] = $date;
    }

    public function render()
    {
        return view('livewire.invoices.invoice-index')->title(__('messages.invoices'));
    }
}
