<?php

namespace App\Livewire\Forms;

use App\Models\Company;
use Livewire\Form;

class CompanyForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
        ];
    }

    public function updateOrCreate() {
        $this->validate();
        Company::updateOrCreate(['id' => $this->id], $this->all());
    }
}
