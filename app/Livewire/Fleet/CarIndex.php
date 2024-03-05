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
        ->when($this->filters['code'],function($q){
            $q->where('code',$this->filters['code']);
        })
        ->paginate(10)
        ;
    }

    public function render()
    {
        return view('livewire.fleet.car-index');
    }
}
