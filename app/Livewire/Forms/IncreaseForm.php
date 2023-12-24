<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class IncreaseForm extends Form
{
    public $id;
    public $employee_id;
    public $increase_date;
    public $amount;
    public $type;
    public $notes;
    public $created_by;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'employee_id' => 'required',
            'increase_date' => 'required',
            'amount' => 'required',
            'type' => 'required',
            'notes' => 'nullable',
            'created_by' => 'required',
        ];
    }
}
