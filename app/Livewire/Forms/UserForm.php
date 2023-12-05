<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UserForm extends Form
{
    public $id;
    public $username;
    public $name_en;
    public $name_ar;
    public $password;
    public $title_id;
    public $department_id;
    public $shift_id;
    public bool $active = true;
    public array $roles;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->id)],
            'name_en' => 'required',
            'name_ar' => 'required',
            'password' => [Rule::requiredIf(!$this->id)],
            'title_id' => 'required',
            'department_id' => 'required',
            'shift_id' => 'nullable',
            'active' => 'nullable',
            'roles' => 'required',
        ];
    }
}
