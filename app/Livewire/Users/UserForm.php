<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm as FormsUserForm;
use App\Models\Department;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Title;
use App\Models\User;
use Livewire\Component;

class UserForm extends Component
{

    public $user;
    public $departments;
    public $titles;
    public $shifts;
    public $roles;

    public FormsUserForm $form;

    public function mount(User $user)
    {
        $this->user = $user->load('roles:id');
        $this->departments = Department::select('id', 'name_en', 'name_ar')->get();
        $this->titles = Title::select('id', 'name_en', 'name_ar')->get();
        $this->shifts = Shift::select('id', 'name_en', 'name_ar')->get();
        $this->roles = Role::select('id', 'name_en', 'name_ar')->get();

        $this->form->fill($this->user);

        $this->form->roles = [];
        foreach($this->user->roles as $role){
            $this->form->roles[] = $role->id;
        }

        if(request()->is_duplicate){
            $this->form->id = null;
            $this->form->name_ar = '';
            $this->form->name_en = '';
            $this->form->username = '';
            $this->form->password = '';
        }
        
    }

    public function save()
    {
        $validated = $this->form->validate();
       
        if ($validated['password']) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            $validated = data_forget($validated, 'password');
        }
        $validated = data_forget($validated, 'roles');
        
        $user = User::updateOrCreate(['id' => $validated['id']], $validated);
        $user->roles()->sync($this->form->roles);
        session()->flash('success', 'User saved successfully.');
        $this->redirect(UserIndex::class, navigate: true);
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
