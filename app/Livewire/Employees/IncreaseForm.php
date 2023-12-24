<?php

namespace App\Livewire\Employees;

use App\Livewire\Forms\IncreaseForm as FormsIncreaseForm;
use App\Models\Employee;
use App\Models\Increase;
use Livewire\Attributes\On;
use Livewire\Component;

class IncreaseForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Employee $employee;
    public Increase $increase;

    public FormsIncreaseForm $form;

    #[On('showIncreaseFormModal')]
    public function show(Increase $increase, Employee $employee)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->increase = $increase;
        $this->employee = $employee;
        $this->modalTitle = $this->increase->id ? __('messages.edit_increase') : __('messages.add_increase');
        $this->form->fill($this->increase);
        if (!$this->increase->id) {
            // create
            $this->form->created_by = auth()->id();
            $this->form->employee_id = $this->employee->id;
        }
    }

    public function save()
    {
        $validated = $this->form->validate();
        Increase::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('increasesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.employees.increase-form');
    }
}
