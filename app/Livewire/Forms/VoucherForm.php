<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class VoucherForm extends Form
{
    public $id;
    public $manual_id;
    public $type = 'jv';
    public $created_by;
    public $date;
    public $notes;
    public $details = [];

    public function rules()
    {
        return [
            'id' => 'nullable',
            'manual_id' => 'nullable',
            'type' => 'required',
            'created_by' => 'required',
            'date' => 'required',
            'notes' => 'nullable',
            'details' => 'required',
            'details.*.account_id' => 'required',
            'details.*.debit' => 'required',
            'details.*.credit' => 'required',
        ];
    }
}
