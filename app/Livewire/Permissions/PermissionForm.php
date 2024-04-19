<?php

namespace App\Livewire\Permissions;

use App\Livewire\Forms\PermissionForm as FormsPermissionForm;
use App\Models\Permission;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class PermissionForm extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Permission $permission;

    public FormsPermissionForm $form;

    #[On('showPermissionFormModal')]
    public function show(Permission $permission)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->permission = $permission;
        $this->modalTitle = $this->permission->id ? __('messages.edit_permission') : __('messages.add_permission');
        $this->form->fill($this->permission);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('permissionsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.permissions.permission-form');
    }
}
