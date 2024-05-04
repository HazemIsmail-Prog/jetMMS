<?php

namespace App\Livewire\Forms;

use App\Models\Title;
use Livewire\Form;

class TitleForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $active = true;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        Title::updateOrCreate(['id' => $this->id], $this->all());
    }
}
