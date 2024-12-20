<?php

namespace App\Livewire\OperationsReports;

use App\Models\Marketing;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MarketingCounter extends Component
{

    // public $selectedDate;

    // public function mount()
    // {
    //     $this->selectedDate = now()->format('m') . '-' . now()->format('Y');
    // }

    // #[Computed()]
    // public function dateFilter()
    // {
    //     return Marketing::query()
    //         ->select(
    //             DB::raw('MONTH(created_at) as month'),
    //             DB::raw('YEAR(created_at) as year'),
    //         )
    //         ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
    //         ->get();
    // }

    // #[Computed()]
    // public function types()
    // {
    //     return Marketing::query()->pluck('type')->unique();
    // }

    // #[Computed()]
    // public function marketings()
    // {
    //     return Marketing::query()
    //         ->whereMonth('created_at', explode('-', $this->selectedDate)[0])
    //         ->whereYear('created_at', explode('-', $this->selectedDate)[1])
    //         ->selectRaw('DATE(created_at) as date, COUNT(*) as count, type')
    //         ->groupBy(DB::raw('DATE(created_at)'), 'type')
    //         ->orderByDesc('date')
    //         ->get();
    // }

    // public function render()
    // {
    //     return view('livewire.operations-reports.marketing-counter');
    // }
}
