<?php

namespace App\Livewire\Employees;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeIndex extends Component
{
    use WithPagination;

    public $titles;
    public $departments;
    public $shifts;
    public $roles;

    #[Url()]
    public $filters = [
        'name' => null,
        'title_id' => '',
        'department_id' => '',
        'shift_id' => '',
        'status' => '',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->titles = Title::select('id', 'name_en', 'name_ar')->get();
        $this->departments = Department::select('id', 'name_en', 'name_ar')->get();
        $this->shifts = Shift::select('id', 'name_en', 'name_ar')->get();
    }

    #[Computed()]
    #[On('attachmentsUpdated')]
    #[On('employeesUpdated')]
    public function employees()
    {
        return Employee::query()

            ->with('user.title')
            ->with('user.department')
            ->with('user.shift')
            ->withCount('attachments')
            ->when($this->filters['name'], function (Builder $q) {
                $q->whereRelation('user', 'name_ar', 'like', '%' . $this->filters["name"] . '%');
                $q->orWhereRelation('user', 'name_en', 'like', '%' . $this->filters["name"] . '%');
            })

            ->when($this->filters['title_id'], function (Builder $q) {
                $q->whereRelation('user', 'title_id', $this->filters["title_id"]);
            })
            ->when($this->filters['department_id'], function (Builder $q) {
                $q->whereRelation('user', 'department_id', $this->filters["department_id"]);
            })
            ->when($this->filters['shift_id'], function (Builder $q) {
                $q->whereRelation('user', 'shift_id', $this->filters["shift_id"]);
            })

            ->when($this->filters['status'], function ($q) {
                $q->where('status', $this->filters["status"]);
            })
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.employees.employee-index');
    }
}
