<?php

namespace App\Livewire\Forms;

use App\Models\CarAction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ActionForm extends Form
{
    public $id;
    public $car_id;
    public $created_by;
    public int | null $from_id;
    public int | null $to_id;
    public $notes;
    public $date;
    public $time;
    public $fuel = '2';
    public $kilos;

    public function rules()
    {
        return [
            'from_id' => 'nullable',
            'to_id' => 'nullable',
            'notes' => 'nullable',
            'date' => 'required',
            'time' => 'required',
            'fuel' => 'required',
            'kilos' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->id) {
            $this->created_by = auth()->id();
        }
        CarAction::updateOrCreate(['id' => $this->id], $this->all());
        $this->reset();

    }
}
