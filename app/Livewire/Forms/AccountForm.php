<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class AccountForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $income_account_id;
    public $cost_account_id;
    public $usage;
    public $level = 0;
    public $index = 0;
    public bool $active = true;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'usage' => 'nullable',
            'level' => 'required',
            'index' => 'required',
            'active' => 'nullable',
            'account_id' => 'nullable',
        ];
    }
}
