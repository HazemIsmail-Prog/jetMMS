<?php

namespace App\Livewire\Fleet;

use App\Livewire\Forms\CarServiceForm;
use App\Models\Car;
use App\Models\CarService;
use Livewire\Attributes\On;
use Livewire\Component;

class ServiceForm extends Component
{
    public $showModal = false;
    public CarService $carService;
    public CarServiceForm $form;

    #[On('showCarServiceFormModal')]
    public function show(CarService $carService, Car $car)
    {
        $this->resetErrorBag();
        $this->carService = $carService;
        $this->form->fill($this->carService);
        $this->form->car_id = $car->id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->showModal = false;
        $this->dispatch('carServicesUpdated');
    }

    public function render()
    {
        return view('livewire.fleet.service-form');
    }
}
