<?php

namespace App\Livewire\Fleet;

use App\Models\Car;
use App\Models\CarAction;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ActionIndex extends Component
{
    use WithPagination;

    public Car $car;

    public function mount(Car $car) {
        $this->car = $car;
    }


    #[Computed]
    public function actions() {
        return CarAction::query()
        ->where('car_id',$this->car->id)
        ->with('driver')
        ->orderBy('id','desc')
        ->simplePaginate()
        ;
    }

    public function render()
    {
        return view('livewire.fleet.action-index');
    }
}
