<?php

namespace App\Livewire\Cars\Services;

use App\Models\Car;
use App\Models\CarService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ServiceIndex extends Component
{
    use WithPagination , WithoutUrlPagination;

    public Car $car;
    public $showModal = false;

    #[On('showCarServicesModal')]
    public function show(Car $car)
    {
        $this->car = $car;
        $this->resetPage();
        $this->showModal = true;
    }

    #[Computed()]
    #[On('carServicesUpdated')]
    public function services()
    {
        return CarService::query()
            ->where('car_id', $this->car->id)
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function delete(CarService $carService)
    {
        $carService->delete();
        $this->dispatch('carServicesUpdated');
    }

    public function render()
    {
        return view('livewire.cars.services.service-index');
    }
}
