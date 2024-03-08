<?php

namespace App\Livewire\Cars\Actions;

use App\Livewire\Forms\CarActionForm;
use App\Models\Car;
use App\Models\CarAction;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ActionForm extends Component
{
    public $showModal = false;
    public CarAction $carAction;
    public CarActionForm $form;

    #[On('showActionFormModal')]
    public function show(CarAction $carAction, Car $car)
    {
        $this->resetErrorBag();
        $this->carAction = $carAction;
        $this->form->fill($this->carAction);
        $this->form->car_id = $car->id;

        // set from_id to latest to_id only on create
        if(!$carAction->id && $car->latest_car_action?->to_id){
            $this->form->from_id = $car->latest_car_action->to_id;
        }

        $this->showModal = true;
    }

    #[Computed()]
    public function users()
    {
        return User::query()
            ->select('id','name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    public function updated($key,$val) {
        if($key == 'form.from_id'){
            $this->form->to_id = null;
        }
        if($key == 'form.to_id'){
            $this->form->from_id = null;
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->showModal = false;
        $this->dispatch('carActionsUpdated');
    }

    public function render()
    {
        return view('livewire.cars.actions.action-form');
    }
}
