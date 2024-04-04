<?php

namespace App\Livewire\Orders;

use App\Exports\OrdersExport;
use App\Models\Area;
use App\Models\Department;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;


class OrderIndex extends Component
{
    use WithPagination;

    public $listeners = [
        'commentsUpdated' => '$refresh',
        'invoicesUpdated' => '$refresh',
    ];

    public int $maxExportSize = 5000;

    #[Url()]
    public $filters =
    [
        'customer_id' => '',
        'customer_name' => '',
        'customer_phone' => '',
        'areas' => '',
        'block' => '',
        'street' => '',
        'order_number' => '',
        'creators' => '',
        'statuses' => '',
        'technicians' => '',
        'departments' => '',
        'tags' => '',
        'start_created_at' => '',
        'end_created_at' => '',
        'start_completed_at' => '',
        'end_completed_at' => '',
        'customer_id' => '',
    ];

    #[Computed()]
    public function areas()
    {
        return Area::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function creators()
    {
        return User::query()
            ->whereHas('orders_creator')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function statuses()
    {
        return Status::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('index')
            ->get();
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->whereHas('orders_technician')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->whereHas('orders')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    // #[Computed(cache:true)]
    // public function tags() {
    //     return Order::whereNotNull('tag')->groupBy('tag')->pluck('tag');
    // }

    public function excel()
    {
        if ($this->getData()->count() > $this->maxExportSize) {
            return;
        } else {
            return Excel::download(new OrdersExport('livewire.orders.excel.excel', 'Orders', $this->getData()->get()), 'Orders.xlsx');  //Excel
        }
    }


    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getData()
    {
        return Order::query()
            ->with('creator')
            ->with('status')
            ->with('department')
            ->with('technician')
            ->with('customer')
            ->with('phone')
            ->with('address')
            ->with('invoices')
            ->withCount('invoices as custom_invoices_count')
            ->withCount('comments as all_comments')
            ->orderBy('id', 'desc')

            ->when($this->filters['customer_id'], function (Builder $q) {
                $q->whereRelation('customer', 'id', $this->filters['customer_id']);
            })
            ->when($this->filters['customer_name'], function (Builder $q) {
                $q->whereRelation('customer', 'name', 'like', '%' . $this->filters['customer_name'] . '%');
            })
            ->when($this->filters['customer_phone'], function (Builder $q) {
                $q->whereRelation('phone', 'number', 'like', $this->filters['customer_phone'] . '%');
            })
            ->when($this->filters['areas'], function (Builder $q) {
                $q->whereRelation('address', 'area_id', $this->filters['areas']);
            })
            ->when($this->filters['block'], function (Builder $q) {
                $q->whereRelation('address', 'block', $this->filters['block']);
            })
            ->when($this->filters['street'], function (Builder $q) {
                $q->whereRelation('address', 'street', $this->filters['street']);
            })
            ->when($this->filters['order_number'], function ($q) {
                $q->where('id', $this->filters['order_number']);
            })
            ->when($this->filters['creators'], function (Builder $q) {
                $q->where('created_by', $this->filters['creators']);
            })
            ->when($this->filters['statuses'], function (Builder $q) {
                $q->where('status_id', $this->filters['statuses']);
            })
            ->when($this->filters['technicians'], function (Builder $q) {
                $q->where('technician_id', $this->filters['technicians']);
            })
            ->when($this->filters['departments'], function (Builder $q) {
                $q->where('department_id', $this->filters['departments']);
            })
            ->when($this->filters['tags'], function (Builder $q) {
                $q->where('tag', $this->filters['tags']);
            })
            ->when($this->filters['start_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '>=', $this->filters['start_created_at']);
            })
            ->when($this->filters['end_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '<=', $this->filters['end_created_at']);
            })
            ->when($this->filters['start_completed_at'], function (Builder $q) {
                $q->whereDate('completed_at', '>=', $this->filters['start_completed_at']);
            })
            ->when($this->filters['end_completed_at'], function (Builder $q) {
                $q->whereDate('completed_at', '<=', $this->filters['end_completed_at']);
            });
    }

    #[Computed]
    #[On('ordersUpdated')]
    #[On('customersUpdated')]
    public function orders()
    {
        return $this->getData()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.orders.order-index');
    }
}
