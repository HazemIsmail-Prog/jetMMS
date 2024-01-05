<?php

namespace App\Livewire\Employees;

use App\Livewire\Forms\AbsenceForm as FormsAbsenceForm;
use App\Models\Absence;
use App\Models\Employee;
use Livewire\Attributes\On;
use Livewire\Component;

class AbsenceForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Employee $employee;
    public Absence $absence;

    public FormsAbsenceForm $form;

    #[On('showAbsenceFormModal')]
    public function show(Absence $absence, Employee $employee)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->absence = $absence;
        $this->employee = $employee;
        $this->modalTitle = $this->absence->id ? __('messages.edit_absence') : __('messages.add_absence');
        $this->form->fill($this->absence);
        if (!$this->absence->id) {
            // create
            $this->form->created_by = auth()->id();
            $this->form->employee_id = $this->employee->id;
        }
    }

    public function updated($key, $val)
    {
        if ($key == 'form.deduction_days') {
            if ($val) {
                $this->form->deduction_amount = round($this->employee->salary_per_day * $val, 3);
            } else {
                $this->form->deduction_amount = null;
            }
        }
    }

    public function save()
    {
        $validated = $this->form->validate();
        Absence::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('absencesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.employees.absence-form');
    }
}
