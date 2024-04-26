<?php

namespace App\Livewire\Roles;

use App\Models\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class RoleIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('rolesUpdated')]
    public function roles()
    {
        return Role::query()
            ->with('permissions')
            ->paginate(15);
    }

    public function delete(Role $role)
    {
        $role->delete();
    }

    public function render()
    {
        return view('livewire.roles.role-index')->title(__('messages.roles'));
    }
}
