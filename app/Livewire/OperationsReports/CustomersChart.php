<?php

namespace App\Livewire\OperationsReports;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CustomersChart extends Component
{
    public $customers = [];
    public $title = '';
    public $completed_title = '';

    public function mount()
    {
        $this->title = __('messages.customers');
        $this->customers = Customer::query()
        ->where('created_at','!=',null)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();

        $this->dispatch('customersFetched');
    }

    public function render()
    {
        return view('livewire.operations-reports.customers-chart');
    }
}
