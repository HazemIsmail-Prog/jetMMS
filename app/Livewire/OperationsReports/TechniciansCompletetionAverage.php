<?php

namespace App\Livewire\OperationsReports;

use App\Models\Department;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TechniciansCompletetionAverage extends Component
{
    public $orders = [];
    public $completed_orders = [];
    public $years = [];
    public $title = '';
    public $completed_title = '';
    // public $departments;

    public function mount()
    {
        // $this->departments = Department::where('is_service',1)->get();
        $this->years =
            Order::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->pluck('year');

        $this->title = __('messages.average_completed_orders_for_technicians');
        $this->completed_title = __('messages.average_completed_orders_for_technicians');

        $this->completed_orders = Order::query()
            ->where('status_id', 4)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT technician_id) as total_technicians'),
                DB::raw('COUNT(*)/COUNT(DISTINCT technician_id) as average'),
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();

        $this->dispatch('ordersFetched');
    }

    public function render()
    {
        // dd($this->completed_orders);
        return view('livewire.operations-reports.technicians-completetion-average');
    }
}
