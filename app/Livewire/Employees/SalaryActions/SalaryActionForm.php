<?php

namespace App\Livewire\Employees\SalaryActions;

use App\Livewire\Forms\SalaryActionForm as FormsSalaryActionForm;
use App\Models\Employee;
use App\Models\SalaryAction;
use Livewire\Attributes\On;
use Livewire\Component;

class SalaryActionForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Employee $employee;
    public SalaryAction $salaryAction;

    public FormsSalaryActionForm $form;

    #[On('showSalaryActionFormModal')]
    public function show(SalaryAction $salaryAction, Employee $employee)
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->showModal = true;
        $this->salaryAction = $salaryAction;
        $this->employee = $employee;
        $this->modalTitle = $this->salaryAction->id ? __('messages.edit_salaryAction') : __('messages.add_salaryAction');
        $this->form->fill($this->salaryAction);
        if (!$this->salaryAction->id) {
            // create
            $this->form->created_by = auth()->id();
            $this->form->employee_id = $this->employee->id;
        }
    }

    public function save()
    {
        $validated = $this->form->validate();
        SalaryAction::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('salaryActionsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.employees.salary-actions.salary-action-form');
    }
}
