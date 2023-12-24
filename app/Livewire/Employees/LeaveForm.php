<?php

namespace App\Livewire\Employees;

use App\Livewire\Forms\LeaveForm as FormsLeaveForm;
use App\Models\Employee;
use App\Models\Leave;
use Livewire\Attributes\On;
use Livewire\Component;

class LeaveForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Employee $employee;
    public Leave $leave;

    public FormsLeaveForm $form;

    #[On('showLeaveFormModal')]
    public function show(Leave $leave, Employee $employee)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->leave = $leave;
        $this->employee = $employee;
        $this->modalTitle = $this->leave->id ? __('messages.edit_leave') : __('messages.add_leave');
        $this->form->fill($this->leave);
        if(!$this->leave->id){
            // create
            $this->form->created_by = auth()->id();
            $this->form->employee_id = $this->employee->id;
        }
    }

    public function save()
    {
        $validated = $this->form->validate();
        Leave::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('leavesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.employees.leave-form');
    }
}
