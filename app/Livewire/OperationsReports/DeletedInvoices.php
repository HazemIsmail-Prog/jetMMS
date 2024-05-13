<?php

namespace App\Livewire\OperationsReports;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DeletedInvoices extends Component
{
    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = now()->format('m') . '-' . now()->format('Y');
    }

    #[Computed()]
    public function dateFilter()
    {
        return Invoice::query()
            ->onlyTrashed()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();
    }

    #[Computed()]
    public function users()
    {
        return User::query()
            ->whereHas('deletedInvoices', function ($q) {
                $q->whereMonth('created_at', explode('-', $this->selectedDate)[0])
                    ->whereYear('created_at', explode('-', $this->selectedDate)[1]);
            })
            ->withCount(['deletedInvoices' => function ($q) {
                $q->whereMonth('created_at', explode('-', $this->selectedDate)[0])
                    ->whereYear('created_at', explode('-', $this->selectedDate)[1]);
            }])
            ->get();
    }

    public function render()
    {
        // dd($this->users);
        return view('livewire.operations-reports.deleted-invoices');
    }
}
