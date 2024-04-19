<?php

namespace App\Livewire\Forms;

use App\Models\Permission;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PermissionForm extends Form
{

    public $id;
    public $name;
    public $section_name_ar;
    public $section_name_en;
    public $desc_ar;
    public $desc_en;

    public function rules()
    {
        return [
            'name' => 'required',
            'section_name_ar' => 'required',
            'section_name_en' => 'required',
            'desc_ar' => 'required',
            'desc_en' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        Permission::updateOrCreate(['id' => $this->id], $this->all());
        $this->reset();
    }
}
