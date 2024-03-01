<?php

namespace App\Livewire\CostCenters;

use App\Models\CostCenter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CostCenterIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('costCentersUpdated')]
    public function cost_centers()
    {
        return CostCenter::query()
            ->paginate(15);
    }

    public function delete(CostCenter $cost_center) {
        $cost_center->delete();
    }

    public function render()
    {
        return view('livewire.cost-centers.cost-center-index');
    }
}
