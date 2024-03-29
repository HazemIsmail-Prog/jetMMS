<?php

namespace App\Livewire\OperationsReports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrdersChart extends Component
{
    public $orders = [];
    public $completed_orders = [];
    public $years = [];
    public $title = '';
    public $completed_title = '';

    public function mount()
    {
        $this->years =
            Order::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->pluck('year');

        $this->title = __('messages.orders_per_month');
        $this->completed_title = __('messages.completed_orders_per_month');
        $this->orders = Order::query()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();


        $this->completed_orders = Order::query()
            ->where('status_id', 4)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();

        $this->dispatch('ordersFetched');
    }

    public function render()
    {
        return view('livewire.operations-reports.orders-chart');
    }
}
