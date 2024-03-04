<?php

namespace App\Livewire\Orders;

use App\Enums\PaymentStatusEnum;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    public $technicians;
    public $departments;
    public $filters;

    public function mount()
    {
        $this->technicians = User::whereHas('orders_technician')->select('id', 'name_en', 'name_ar')->get();
        $this->departments = Department::whereHas('orders')->select('id', 'name_en', 'name_ar')->get();

        $this->filters =
            [
                'invoice_id' => '',
                'order_id' => '',
                'start_created_at' => '',
                'end_created_at' => '',
                'department_id' => '',
                'technician_id' => '',
                'customer_name' => '',
                'customer_phone' => '',
                'payment_status' => '',
                'customer_id' => '',
                'payment_status' => '',
            ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    #[On('invoicesUpdated')]
    public function invoices()
    {
        return Invoice::query()
            ->orderBy('id', 'desc')
            ->with('order.department')
            ->with('order.customer')
            ->with('order.phone')
            ->with('order.technician')
            ->with('invoice_details.service')
            ->with('payments')

            ->when($this->filters['payment_status'], function (Builder $q) {
                $q->where('payment_status',$this->filters['payment_status'] );
            })
            ->when($this->filters['customer_name'], function (Builder $q) {
                $q->whereRelation('order.customer', 'name', 'like', '%' . $this->filters['customer_name'] . '%');
            })
            ->when($this->filters['customer_phone'], function (Builder $q) {
                $q->whereRelation('order.phone', 'number', 'like', $this->filters['customer_phone'] . '%');
            })
            ->when($this->filters['order_id'], function ($q) {
                $q->where('id', $this->filters['order_id']);
            })
            ->when($this->filters['technician_id'], function (Builder $q) {
                $q->whereRelation('order','technician_id', $this->filters['technician_id']);
            })
            ->when($this->filters['department_id'], function (Builder $q) {
                $q->whereRelation('order','department_id', $this->filters['department_id']);
            })
            ->when($this->filters['start_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '>=', $this->filters['start_created_at']);
            })
            ->when($this->filters['end_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '<=', $this->filters['end_created_at']);
            })

            ->paginate(15);
    }

    public function delete(Invoice $invoice) {
        $invoice->payments()->delete();
        $invoice->delete();
        $this->dispatch('invoicesUpdated');
    }

    public function dateClicked($date) {
        $this->filters['start_created_at'] = $date;
        $this->filters['end_created_at'] = $date;
    }

    public function render()
    {
        return view('livewire.orders.invoice-index');
    }
}
