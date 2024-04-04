<?php

namespace App\Livewire\Forms;

use App\Models\Role;
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

    public function updateOrCreate() {
        $this->validate();
        $role = Role::updateOrCreate(['id' => $this->id], $this->except('permissions'));
        $role->permissions()->sync($this->permissions);
    }
}
