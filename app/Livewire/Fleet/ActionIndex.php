<?php

namespace App\Livewire\Fleet;

use App\Models\Car;
use App\Models\CarAction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ActionIndex extends Component
{
    use WithPagination;

    public Car $car;
    public $showModal = false;

    #[On('showCarActionsModal')]
    public function show(Car $car) {
        $this->car = $car;
        $this->showModal = true;
        
    }

    #[Computed]
    #[On('carActionsUpdated')]
    public function actions() {
        return CarAction::query()
        ->where('car_id',$this->car->id)
        ->with('from')
        ->with('to')
        ->orderBy('date','desc')
        ->orderBy('time','desc')
        ->paginate(10)
        ;
    }

    public function delete(CarAction $carAction) {
        $carAction->delete();
        $this->dispatch('carActionsUpdated');
    }

    public function render()
    {
        return view('livewire.fleet.action-index');
    }
}
