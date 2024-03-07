<?php

namespace App\Livewire\Forms;

use App\Models\CarService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CarServiceForm extends Form
{
    public $id;
    public $car_id;
    public $created_by;
    public $notes;
    public $date;
    public $cost;

    public function rules()
    {
        return [
            'notes' => 'required',
            'date' => 'required',
            'cost' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->id) {
            $this->created_by = auth()->id();
        }
        CarService::updateOrCreate(['id' => $this->id], $this->all());
        $this->reset();
    }
}
