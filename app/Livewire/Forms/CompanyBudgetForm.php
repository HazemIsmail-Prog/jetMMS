<?php

namespace App\Livewire\Forms;

use App\Models\CompanyBudget;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CompanyBudgetForm extends Form
{
    public $id;
    public $company_id;
    public $user_id;
    public $year;
    public $description;
    public $notes;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'company_id' => 'required',
            'user_id' => 'required',
            'year' => 'nullable',
            'description' => 'nullable',
            'notes' => 'nullable',
        ];
    }

    public function updateOrCreate() {
        if (!$this->id) {
            $this->user_id = auth()->id();
        }
        $this->validate();
        CompanyBudget::updateOrCreate(['id' => $this->id], $this->all());
    }
}
