<?php

namespace App\Livewire\Cars;

use App\Livewire\Forms\CarForm as FormsCarForm;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class CarForm extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Car $car;

    public FormsCarForm $form;

    #[On('showCarFormModal')]
    public function show(Car $car)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->car = $car;
        $this->modalTitle = $this->car->id ? __('messages.edit_car') . ' ' . $this->car->code : __('messages.add_car');
        $this->form->fill($this->car);
    }

    #[Computed()]
    public function companies() {
        return Company::query()
        ->select('id' , 'name_en' , 'name_ar' , 'name_' . app()->getLocale() . ' as name')
        ->orderBy('name')
        ->get();
    }

    #[Computed()]
    public function car_brands() {
        return CarBrand::query()
        ->select('id' , 'name_en' , 'name_ar' , 'name_' . app()->getLocale() . ' as name')
        ->orderBy('name')
        ->get();
    }

    #[Computed()]
    public function car_types() {
        return CarType::query()
        ->select('id' , 'name_en' , 'name_ar' , 'name_' . app()->getLocale() . ' as name')
        ->orderBy('name')
        ->get();
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('carsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.cars.car-form');
    }
}
