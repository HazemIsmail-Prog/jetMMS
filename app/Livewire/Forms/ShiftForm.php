<?php

namespace App\Livewire\Forms;

use App\Models\Shift;
use Livewire\Form;

class ShiftForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $start_time;
    public $end_time;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        Shift::updateOrCreate(['id' => $this->id], $this->all());
    }
}
