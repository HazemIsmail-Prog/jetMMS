<?php

namespace App\Livewire\Forms;

use App\Models\Department;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DepartmentForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $income_account_id;
    public $cost_account_id;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'income_account_id' => 'nullable',
            'cost_account_id' => 'nullable',
        ];
    }

    public function updateOrCreate() {
        $this->validate();
        Department::updateOrCreate(['id' => $this->id], $this->all());
    }
}
