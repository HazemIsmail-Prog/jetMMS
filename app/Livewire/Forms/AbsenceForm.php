<?php

namespace App\Livewire\Forms;

use App\Rules\OverlappingAbsencePeriods;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AbsenceForm extends Form
{
    public $id;
    public $employee_id;
    public $start_date;
    public $end_date;
    public $deduction_days;
    public $deduction_amount;
    public $notes;
    public $created_by;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'employee_id' => 'required',
            'start_date' => ['required', 'before_or_equal:end_date', new OverlappingAbsencePeriods()],
            'end_date' => ['required', 'after_or_equal:end_date', new OverlappingAbsencePeriods()],
            'deduction_days' => 'required',
            'deduction_amount' => 'required',
            'notes' => 'nullable',
            'created_by' => 'required',
        ];
    }
}
