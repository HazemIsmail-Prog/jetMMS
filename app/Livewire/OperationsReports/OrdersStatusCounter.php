<?php

namespace App\Livewire\OperationsReports;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class OrdersStatusCounter extends Component
{
    // public $selectedDate;

    // public function mount()
    // {
    //     $this->selectedDate = now()->format('m') . '-' . now()->format('Y');
    // }

    // #[Computed()]
    // public function statuses()
    // {
    //     return Status::orderBy('index')->get();
    // }

    // #[Computed()]
    // public function dateFilter()
    // {
    //     return Order::query()
    //         ->select(
    //             DB::raw('MONTH(created_at) as month'),
    //             DB::raw('YEAR(created_at) as year'),
    //         )
    //         ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
    //         ->get();
    // }

    // #[Computed()]
    // public function counters()
    // {
    //     return Order::query()
    //         ->whereMonth('created_at', explode('-', $this->selectedDate)[0])
    //         ->whereYear('created_at', explode('-', $this->selectedDate)[1])
    //         ->selectRaw('DATE(created_at) as date, COUNT(*) as count, status_id')
    //         ->groupBy(DB::raw('DATE(created_at)'), 'status_id')
    //         ->orderByDesc('date')
    //         ->get();
    // }

    // public function render()
    // {
    //     return view('livewire.operations-reports.orders-status-counter');
    // }
}
