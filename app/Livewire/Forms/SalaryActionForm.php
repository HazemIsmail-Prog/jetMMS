<?php

namespace App\Livewire\Forms;

use App\Enums\SalaryActionStatusEnum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SalaryActionForm extends Form
{
    public $id;
    public $employee_id;
    public $created_by;
    public $date;
    public $amount;
    public $type;
    public $reason;
    public $status = SalaryActionStatusEnum::PENDING;
    public $notes;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'employee_id' => 'required',
            'created_by' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'type' => 'required',
            'reason' => 'required',
            'status' => 'required',
            'notes' => 'nullable',
        ];
    }
}
