<?php

namespace App\Livewire\OperationsReports;

use App\Models\Department;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DepartmentTechnicianCounter extends Component
{
    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = now()->format('m') . '-' . now()->format('Y');
    }

    #[Computed()]
    public function dateFilter()
    {
        return Order::query()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', 1)
            ->with(['technicians' => function ($q) {
                $q->withCount(['orders_technician as completed_orders_count' => function ($q) {
                    $q->whereNotNull('completed_at');
                    $q->whereMonth('created_at', explode('-', $this->selectedDate)[0]);
                    $q->whereYear('created_at', explode('-', $this->selectedDate)[1]);
                }]);
            }])
            ->withCount(['orders as completed_orders_count' => function ($q) {
                $q->whereNotNull('completed_at');
                $q->whereMonth('created_at', explode('-', $this->selectedDate)[0]);
                $q->whereYear('created_at', explode('-', $this->selectedDate)[1]);
            }])
            ->withCount(['orders as total_orders_count' => function ($q) {
                $q->whereMonth('created_at', explode('-', $this->selectedDate)[0]);
                $q->whereYear('created_at', explode('-', $this->selectedDate)[1]);
            }])
            ->get();
    }

    public function render()
    {
        return view('livewire.operations-reports.department-technician-counter');
    }
}
