<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;
    public $permissions = [];

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
            'permissions' => 'required',
        ];
    }
}
