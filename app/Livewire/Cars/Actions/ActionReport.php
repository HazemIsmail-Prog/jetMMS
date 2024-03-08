<?php

namespace App\Livewire\Cars\Actions;

use App\Models\CarAction;
use Livewire\Component;

class ActionReport extends Component
{

    public $action;
    public $title_en;
    public $title_ar;

    public function mount(CarAction $action) {
        $this->action = $action;
        $this->title_ar = $action->type == 'assign' ? 'نموذج تسليم سيارة للسائق' : 'نموذج استلام سيارة من السائق';
        $this->title_en = $action->type == 'assign' ? 'Delivery for Driver Form' : 'Receive From Driver Form';
    }

    public function render()
    {
        return view('livewire.cars.actions.action-report');
    }
}
