<?php

namespace App\Livewire\Employees;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\Title;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeIndex extends Component
{
    use WithPagination;

    #[Url()]
    public $filters = [
        'name' => null,
        'title_id' => [],
        'department_id' => [],
        'shift_id' => [],
        'status' => '',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    public function titles()
    {
        return Title::query()
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
    public function shifts()
    {
        return Shift::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
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
                $q->whereHas('user', function (Builder $q) {
                    $q->whereIn('title_id', $this->filters["title_id"]);
                });
            })
            ->when($this->filters['department_id'], function (Builder $q) {
                $q->whereHas('user', function (Builder $q) {
                    $q->whereIn('department_id', $this->filters["department_id"]);
                });
            })
            ->when($this->filters['shift_id'], function (Builder $q) {
                $q->whereHas('user', function (Builder $q) {
                    $q->whereIn('shift_id', $this->filters["shift_id"]);
                });
            })

            ->when($this->filters['status'], function ($q) {
                $q->where('status', $this->filters["status"]);
            })
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->orderBy('users.department_id')
            ->orderBy('users.title_id')
            ->orderBy('users.name_'. app()->getLocale())
            ->paginate(1500);
    }

    public function delete(Employee $employee)
    {
        $employee->delete();
    }

    public function render()
    {
        return view('livewire.employees.employee-index')->title(__('messages.employees'));
    }
}
