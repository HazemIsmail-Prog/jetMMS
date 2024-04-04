<?php

namespace App\Livewire\Forms;

use App\Models\Service;
use Livewire\Form;

class ServiceForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $min_price;
    public $max_price;
    public $department_id;
    public $type;
    public bool $active = true;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'min_price' => 'required',
            'max_price' => 'required',
            'department_id' => 'required',
            'type' => 'required',
            'active' => 'nullable',
        ];
    }

    public function updateOrCreate() {
        $this->validate();
        Service::updateOrCreate(['id' => $this->id], $this->all());
    }
}
