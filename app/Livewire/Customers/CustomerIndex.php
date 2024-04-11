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
            ->with('orders')
            ->with('invoices')
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
            ->paginate(15);
    }

    public function delete(Customer $customer) {
        $customer->delete();
    }

    public function render()
    {
        return view('livewire.customers.customer-index');
    }
}
