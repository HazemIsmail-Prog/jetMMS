<?php

namespace App\Livewire\Fleet;

use App\Models\Car;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CarIndex extends Component
{
    use WithPagination;

    public $filters = ['code' => ''];


    #[Computed]
    public function cars() {
        return Car::query()
        ->with('brand')
        ->with('type')
        ->with('driver')
        ->with('technician')
        ->withCount('actions')
        ->when($this->filters['code'],function($q){
            $q->where('code',$this->filters['code']);
        })
        ->simplePaginate()
        ;
    }

    public function showActionFormModal($car_id) {
        $this->dispatch('showActionFormModal', car_id: $car_id);
    }

    public function render()
    {
        return view('livewire.fleet.car-index');
    }
}
