<?php

namespace App\Livewire\Contracts;

use App\Models\Area;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;


class ContractIndex extends Component
{
    use WithPagination;

    public $contract_type;

    public function mount()
    {
        $routeName = Route::currentRouteName();
        $this->contract_type = explode(".",$routeName)[0];
    }

    #[Url()]
    public $filters =
    [
        'customer_id' => '',
        'customer_name' => '',
        'customer_phone' => '',
        'areas' => [],
        'block' => '',
        'street' => '',
        'contract_number' => '',
        'creators' => [],
        'start_contract_date' => '',
        'end_contract_date' => '',
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
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->whereHas('contracts')
            ->get();
    }

    // public function excel()
    // {
    //     if ($this->getData()->count() > $this->maxExportSize) {
    //         return;
    //     } else {
    //         return Excel::download(new OrdersExport('livewire.orders.excel.excel', 'Orders', $this->getData()->get()), 'Orders.xlsx');  //Excel
    //     }
    // }


    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getData()
    {
        return Contract::query()
            ->where('contract_type', $this->contract_type)
            ->with('user:id,name_ar,name_en')
            ->with('customer:id,name')
            ->with('address')
            ->withCount('attachments')
            ->orderBy('id', 'desc')

            ->when($this->filters['customer_id'], function (Builder $q) {
                $q->whereRelation('customer', 'id', $this->filters['customer_id']);
            })
            ->when($this->filters['customer_name'], function (Builder $q) {
                $q->whereRelation('customer', 'name', 'like', '%' . $this->filters['customer_name'] . '%');
            })
            // ->when($this->filters['customer_phone'], function (Builder $q) {
            //     $q->whereRelation('phone', 'number', 'like', $this->filters['customer_phone'] . '%');
            // })
            ->when($this->filters['areas'], function (Builder $q) {
                $q->whereHas('address', function (Builder $q) {
                    $q->whereIn('area_id', $this->filters['areas']);
                });
            })
            ->when($this->filters['block'], function (Builder $q) {
                $q->whereRelation('address', 'block', $this->filters['block']);
            })
            ->when($this->filters['street'], function (Builder $q) {
                $q->whereRelation('address', 'street', $this->filters['street']);
            })
            ->when($this->filters['contract_number'], function ($q) {
                $q->where('contract_number', $this->filters['contract_number']);
            })
            ->when($this->filters['creators'], function (Builder $q) {
                $q->whereIn('user_id', $this->filters['creators']);
            })
            ->when($this->filters['start_contract_date'], function (Builder $q) {
                $q->whereDate('contract_date', '>=', $this->filters['start_contract_date']);
            })
            ->when($this->filters['end_contract_date'], function (Builder $q) {
                $q->whereDate('contract_date', '<=', $this->filters['end_contract_date']);
            });
    }

    #[Computed]
    #[On('contractsUpdated')]
    #[On('attachmentsUpdated')]
    public function contracts()
    {
        return $this->getData()
            ->paginate(10);
    }

    public function delete(Contract $contract) {
        $contract->delete();
    }

    public function render()
    {
        return view('livewire.contracts.contract-index');
    }
}
