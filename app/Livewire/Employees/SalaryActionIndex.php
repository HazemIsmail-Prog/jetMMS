<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use App\Models\SalaryAction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class SalaryActionIndex extends Component
{
    public Employee $employee;

    #[Computed()]
    #[On('salaryActionsUpdated')]
    #[On('attachmentsUpdated')]
    public function salaryActions()
    {
        return SalaryAction::query()
            ->where('employee_id', $this->employee->id)
            ->orderBy('date', 'desc')
            ->withCount('attachments')
            ->get();
    }

    public function delete(SalaryAction $salaryAction)
    {
        // TODO:delete attachments with its observer
        $salaryAction->delete();
        $this->dispatch('salaryActionsUpdated');
    }

    public function render()
    {
        return view('livewire.employees.salary-action-index');
    }
}
