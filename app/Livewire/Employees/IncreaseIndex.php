<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use App\Models\Increase;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class IncreaseIndex extends Component
{
    public Employee $employee;

    #[Computed()]
    #[On('increasesUpdated')]
    #[On('attachmentsUpdated')]
    public function increases()
    {
        return Increase::query()
            ->where('employee_id', $this->employee->id)
            ->withCount('attachments')
            ->get();
    }

    public function delete(Increase $increase)
    {
        // TODO:delete attachments with its observer
        $increase->delete();
        $this->dispatch('increasesUpdated');
    }

    public function render()
    {
        return view('livewire.employees.increase-index');
    }
}
