<?php

namespace App\Livewire\Fleet;

use App\Models\Car;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CarIndex extends Component
{
    use WithPagination;

    public $filters = ['code' => ''];


    #[Computed]
    #[On('carsUpdated')]
    #[On('carActionsUpdated')]
    public function cars()
    {
        return Car::query()
            ->with('brand')
            ->with('type')
            ->with('latest_car_action.to.department')
            ->withCount('car_actions')
            ->when($this->filters['code'], function ($q) {
                $q->where('code', $this->filters['code']);
            })
            ->paginate(15);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.fleet.car-index');
    }
}
