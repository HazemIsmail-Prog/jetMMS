<?php

namespace App\Livewire\Forms;

use App\Models\Status;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StatusForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $color;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'color' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        Status::updateOrCreate(['id' => $this->id], $this->all());
    }
}
