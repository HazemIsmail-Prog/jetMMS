<?php

namespace App\Livewire\Forms;

use App\Models\Supplier;
use Livewire\Form;

class SupplierForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $account_id;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'account_id' => 'nullable',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        Supplier::updateOrCreate(['id' => $this->id], $this->all());
    }
}
