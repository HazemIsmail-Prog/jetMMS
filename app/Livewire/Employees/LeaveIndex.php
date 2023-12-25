<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use App\Models\Leave;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class LeaveIndex extends Component
{
    public Employee $employee;

    #[Computed()]
    #[On('leavesUpdated')]
    #[On('attachmentsUpdated')]
    public function leaves()
    {
        return Leave::query()
            ->where('employee_id', $this->employee->id)
            ->orderBy('start_date', 'desc')
            ->withCount('attachments')
            ->get();
    }

    public function delete(Leave $leave)
    {
        // TODO:delete attachments with its observer
        $leave->delete();
        $this->dispatch('leavesUpdated');
    }

    public function render()
    {
        return view('livewire.employees.leave-index');
    }
}
