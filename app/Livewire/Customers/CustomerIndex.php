<?php

namespace App\Livewire\Customers;

use App\Models\Area;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public $filters = [
        'name' => '',
        'phone' => '',
        'area_id' => [],
        'block' => '',
        'street' => '',
        'start_created_at' => '',
        'end_created_at' => '',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    public function areas()
    {
        return Area::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    #[On('ordersUpdated')]
    #[On('customersUpdated')]
    public function customers()
    {
        return Customer::query()
            ->orderBy('id', 'desc')
            ->with('phones')
            ->with('addresses')
            ->withCount('orders')
            ->withCount('contracts')
            ->with('invoices.invoice_details')
            ->with('invoices.invoice_part_details')
            ->with('invoices.payments')
            ->when($this->filters['name'], function ($q) {
                $q->where('name', 'like', '%' . $this->filters["name"] . '%');
            })
            ->when($this->filters['phone'], function ($q) {
                $q->whereRelation('phones', 'number', 'like', '%' . $this->filters["phone"] . '%');
            })
            ->when($this->filters['area_id'], function ($q) {
                $q->whereHas('addresses',function( Builder $q){
                    $q->whereIn('area_id', $this->filters["area_id"]);
                });
            })
            ->when($this->filters['block'], function ($q) {
                $q->whereRelation('addresses', 'block', $this->filters["block"]);
            })
            ->when($this->filters['street'], function ($q) {
                $q->whereRelation('addresses', 'street', $this->filters["street"]);
            })
            ->when($this->filters['start_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '>=', $this->filters['start_created_at']);
            })
            ->when($this->filters['end_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '<=', $this->filters['end_created_at']);
            })
            ->paginate(15);
    }

    public function delete(Customer $customer) {
        $customer->delete();
    }

    public function render()
    {
        return view('livewire.customers.customer-index')->title(__('messages.customers'));
    }
}
