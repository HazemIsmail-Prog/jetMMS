<?php

namespace App\Livewire\Fleet;

use App\Livewire\Forms\CarForm as FormsCarForm;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\Company;
use App\Models\User;
use Livewire\Component;

class CarForm extends Component
{

    public FormsCarForm $form;

    public $companies;
    public $car_brands;
    public $car_types;
    public $users;
    public $car;

    public function mount(Car $car)
    {
        $this->car = $car;
        $this->companies = Company::select('id', 'name_ar', 'name_en')->get();
        $this->car_brands = CarBrand::select('id', 'name_ar', 'name_en')->get();
        $this->car_types = CarType::select('id', 'name_ar', 'name_en')->get();
        $this->users = User::select('id', 'name_ar', 'name_en')->get();
        $this->form->fill($car);
    }

    public function updated($key){
        if($key == 'form.has_installment'){
            $this->form->reset('installment_company');
        }
    }

    public function save()
    {
        $validated = $this->form->validate();
        $validated['driver_id'] = $validated['driver_id'] == "" ? null : $validated['driver_id'];
        $validated['technician_id'] = $validated['technician_id'] == "" ? null : $validated['technician_id'];
        Car::updateOrCreate(['id' => $validated['id']], $validated);
        $this->form->reset();
        session()->flash('success', 'Car saved successfully.');
        $this->redirect(CarIndex::class,navigate:true);
    }

    public function render()
    {
        return view('livewire.fleet.car-form');
    }
}
