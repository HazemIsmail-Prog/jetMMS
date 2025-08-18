<?php

namespace App\Livewire\Forms;

use App\Models\Department;
use Illuminate\Support\Facades\Cache;
use Livewire\Form;

class DepartmentForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $income_account_id;
    public $cost_account_id;
    public $cash_account_id;
    public $receivables_account_id;
    public $bank_account_id;
    public $bank_charges_account_id;
    public $internal_parts_account_id;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'income_account_id' => 'nullable',
            'cost_account_id' => 'nullable',
            'cash_account_id' => 'nullable',
            'receivables_account_id' => 'nullable',
            'bank_account_id' => 'nullable',
            'bank_charges_account_id' => 'nullable',
            'internal_parts_account_id' => 'nullable',
        ];
    }

    public function updateOrCreate() {
        $this->validate();
        Department::updateOrCreate(['id' => $this->id], $this->all());
        Cache::forget('sidebar-departments');
    }
}
