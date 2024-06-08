<?php

namespace App\Livewire\Cars;

use App\Models\Car;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CarIndex extends Component
{
    use WithPagination;

    public $filters = ['car_code' => ''];

    #[Computed]
    #[On('carsUpdated')]
    #[On('carActionsUpdated')]
    #[On('carServicesUpdated')]
    #[On('attachmentsUpdated')]
    public function cars()
    {
        return Car::query()
            ->with('brand')
            ->with('type')
            ->with('latest_car_action.to.department')
            ->withCount('attachments')
            ->withCount('car_actions')
            ->withCount('car_services')
            ->withSum('car_services','cost')
            ->when($this->filters['car_code'], function ($q) {
                $q->where('code', $this->filters['car_code']);
            })
            ->orderBy('notes')
            ->paginate(15);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function delete(Car $car) {
        $car->delete();
    }

    public function render()
    {
        return view('livewire.cars.car-index')->title(__('messages.cars'));
    }
}
