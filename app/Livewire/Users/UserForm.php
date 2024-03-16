<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm as FormsUserForm;
use App\Models\Department;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Title;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class UserForm extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public $newExpectedUsername;
    public User $user;

    public FormsUserForm $form;

    #[On('showUserFormModal')]
    public function show(User $user ,User $copiedUser)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->user = $user;
        $this->form->fill($this->user);
        $this->form->roles = $this->user->roles->pluck('id');

        if($copiedUser->id){
            $this->form->title_id = $copiedUser->title_id;
            $this->form->department_id = $copiedUser->department_id;
            $this->form->shift_id = $copiedUser->shift_id;
            $this->form->roles = $copiedUser->roles->pluck('id');
        }

        $this->getExpectedNewUsername();
    }

    public function getExpectedNewUsername()
    {
        if (!$this->user->id) {
            // Only on Create Mode
            for ($i = 25; $i <= 500; $i++) {
                if (!User::pluck('username')->contains($i)) {
                    $this->newExpectedUsername = $i;
                    return;
                }
            }
        }
    }

    #[Computed()]
    public function departments()
    {
        return Department::select('id', 'name_en', 'name_ar')->get();
    }

    #[Computed()]
    public function titles()
    {
        return Title::select('id', 'name_en', 'name_ar')->get();
    }

    #[Computed()]
    public function shifts()
    {
        return Shift::select('id', 'name_en', 'name_ar')->get();
    }

    #[Computed()]
    public function roles()
    {
        return Role::select('id', 'name_en', 'name_ar')->get();
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('usersUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
