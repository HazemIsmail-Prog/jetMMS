<?php

namespace App\Livewire\Customers;

use App\Models\Area;
use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public $areas = [];
    public $filters = [
        'name' => '',
        'phone' => '',
        'area_id' => '',
        'block' => '',
        'street' => '',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->areas = Area::select('id', 'name_en', 'name_ar')->get();
    }

    #[Computed]
    public function customers()
    {
        return Customer::query()
            ->orderBy('id', 'desc')
            ->with('phones')
            ->with('addresses')
            ->with('invoices')
            ->when($this->filters['name'], function ($q) {
                $q->where('name', 'like', '%' . $this->filters["name"] . '%');
            })
            ->when($this->filters['phone'], function ($q) {
                $q->whereRelation('phones','number', 'like', '%' . $this->filters["phone"] . '%');
            })
            ->when($this->filters['area_id'], function ($q) {
                $q->whereRelation('addresses','area_id', $this->filters["area_id"]);
            })
            ->when($this->filters['block'], function ($q) {
                $q->whereRelation('addresses','block', $this->filters["block"]);
            })
            ->when($this->filters['street'], function ($q) {
                $q->whereRelation('addresses','street', $this->filters["street"]);
            })
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.customers.customer-index');
    }
}
