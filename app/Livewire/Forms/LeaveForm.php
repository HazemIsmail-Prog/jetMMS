<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LeaveForm extends Form
{
    public $id;
    public $employee_id;
    public $start_date;
    public $end_date;
    public $type;
    public $status;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'employee_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'type' => 'required',
            'status' => 'required',
        ];
    }
}
