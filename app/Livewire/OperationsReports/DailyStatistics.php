<?php

namespace App\Livewire\OperationsReports;

use App\Models\Customer;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DailyStatistics extends Component
{

    // #[Computed()]
    // public function todaysCompletedOrdersCount() {
    //     return Order::select('id')->whereDate('completed_at', today())->count();
    // }
    // #[Computed()]
    // public function todaysCancelledOrdersCount() {
    //     return Order::select('id')->whereDate('cancelled_at',today())->count();
    // }
    // #[Computed()]
    // public function todaysCustomersCount() {
    //     return Customer::select('id')->whereDate('created_at',today())->count();
    // }
    // public function render()
    // {
    //     return view('livewire.operations-reports.daily-statistics');
    // }
}
