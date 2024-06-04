<?php

namespace App\Livewire\Departments;

use App\Models\Department;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('departmentsUpdated')]
    public function departments()
    {
        return Department::query()
        ->with('incomeAccount')
        ->with('costAccount')
            ->paginate(1500);
    }

    public function delete(Department $department) {
        $department->delete();
    }

    public function render()
    {
        return view('livewire.departments.department-index')->title(__('messages.departments'));
    }
}
