<?php

namespace App\Livewire\Forms;

use App\Models\Account;
use Livewire\Form;

class AccountForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $account_id;
    public $type;
    public $level = 0;
    public $index = 0;
    public bool $active = true;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'type' => 'nullable',
            'level' => 'required',
            'index' => 'required',
            'active' => 'nullable',
            'account_id' => 'nullable',
        ];
    }

    public function updateOrCreate() {
        $this->validate();
        Account::updateOrCreate(['id' => $this->id], $this->all());
    }
}
