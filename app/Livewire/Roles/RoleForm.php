<?php

namespace App\Livewire\Roles;

use App\Livewire\Forms\RoleForm as FormsRoleForm;
use App\Models\Permission;
use App\Models\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class RoleForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Role $role;

    public FormsRoleForm $form;

    #[On('showRoleFormModal')]
    public function show(Role $role)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->role = $role;
        $this->modalTitle = $this->role->id ? __('messages.edit_role') . ' ' . $this->role->name : __('messages.add_role');
        $this->form->fill($this->role);
        $this->form->permissions = $this->role->permissions->pluck('id');
    }

    #[Computed()]
    public function permissions()
    {
        return Permission::get()->groupBy('section_name_' . app()->getLocale());
    }

    public function save()
    {
        $validated = $this->form->validate();
        $validated = data_forget($validated, 'permissions');
        $role = Role::updateOrCreate(['id' => $validated['id']], $validated);
        $role->permissions()->sync($this->form->permissions);
        $this->dispatch('rolesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.roles.role-form');
    }
}
