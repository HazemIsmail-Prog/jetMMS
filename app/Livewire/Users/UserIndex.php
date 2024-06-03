<?php

namespace App\Livewire\Users;

use App\Models\Department;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $perPage = 1500;
    public $filters = [
        'name' => '',
        'username' => '',
        'title_id' => [],
        'department_id' => [],
        'shift_id' => [],
        'role_id' => [],
        'status' => 'all',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }
    
    #[Computed()]
    public function roles() {
        return Role::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function shifts() {
        return Shift::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function titles() {
        return Title::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    #[On('usersUpdated')]
    public function users()
    {
        return User::query()
            ->orderBy('active', 'desc')
            ->orderBy('shift_id')
            ->orderBy('department_id')
            ->orderBy('title_id')
            ->with('title')
            ->with('department')
            ->with('roles')
            ->with('shift')
            ->when($this->filters['name'], function ($q) {
                $q->where('name_ar', 'like', '%' . $this->filters["name"] . '%');
                $q->OrWhere('name_en', 'like', '%' . $this->filters["name"] . '%');
            })
            ->when($this->filters['username'], function ($q) {
                $q->where('username', $this->filters["username"]);
            })
            ->when($this->filters['title_id'], function ($q) {
                $q->whereIn('title_id', $this->filters["title_id"]);
            })
            ->when($this->filters['department_id'], function ($q) {
                $q->whereIn('department_id', $this->filters["department_id"]);
            })
            ->when($this->filters['shift_id'], function ($q) {
                $q->whereIn('shift_id', $this->filters["shift_id"]);
            })
            ->when($this->filters['role_id'], function (Builder $q) {
                $q->whereHas('roles', function(Builder $q){
                    $q->whereIn('role_id', $this->filters['role_id']);
                });
            })
            ->when($this->filters['status'] != 'all', function ($q) {
                $q->where('active', $this->filters["status"]);
            })
            ->paginate($this->perPage);
    }

    public function delete(User $user) {
        $user->delete();
    }

    public function change_status(User $user) {
        $user->active = !$user->active;
        $user->save();
    }

    public function render()
    {
        return view('livewire.users.user-index')->title(__('messages.users'));
    }
}
