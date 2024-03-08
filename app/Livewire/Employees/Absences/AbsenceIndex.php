<?php

namespace App\Livewire\Employees\Absences;

use App\Models\Absence;
use App\Models\Employee;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class AbsenceIndex extends Component
{
    public Employee $employee;

    #[Computed()]
    #[On('absencesUpdated')]
    #[On('attachmentsUpdated')]
    public function absences()
    {
        return Absence::query()
            ->where('employee_id', $this->employee->id)
            ->orderBy('start_date', 'desc')
            ->withCount('attachments')
            ->get();
    }

    public function delete(Absence $absence)
    {
        // TODO:delete attachments with its observer
        $absence->delete();
        $this->dispatch('absencesUpdated');
    }

    public function render()
    {
        return view('livewire.employees.absences.absence-index');
    }
}
