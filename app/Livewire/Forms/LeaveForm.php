<?php

namespace App\Livewire\Forms;

use App\Enums\LeaveStatusEnum;
use App\Rules\OverlappingLeavePeriods;
use Livewire\Form;

class LeaveForm extends Form
{
    public $id;
    public $employee_id;
    public $start_date;
    public $end_date;
    public $type;
    public $status = LeaveStatusEnum::PENDING;
    public $notes;
    public $created_by;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'employee_id' => 'required',
            'start_date' => ['required', 'before_or_equal:end_date', new OverlappingLeavePeriods()],
            'end_date' => ['required', 'after_or_equal:end_date', new OverlappingLeavePeriods()],
            'type' => 'required',
            'status' => 'required',
            'notes' => 'nullable',
            'created_by' => 'required',
        ];
    }
}
