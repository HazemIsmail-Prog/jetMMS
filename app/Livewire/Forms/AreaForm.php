<?php

namespace App\Livewire\Forms;

use App\Models\Area;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AreaForm extends Form
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
        Area::updateOrCreate(['id' => $this->id], $this->all());
    }
}
