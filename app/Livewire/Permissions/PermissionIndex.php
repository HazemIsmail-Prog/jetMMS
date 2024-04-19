<?php

namespace App\Livewire\Permissions;

use App\Models\Permission;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('permissionsUpdated')]
    public function permissions()
    {
        return Permission::query()
            ->paginate(1500);
    }

    public function delete(Permission $permission) {
        $permission->delete();
    }

    public function render()
    {
        return view('livewire.permissions.permission-index');
    }
}
