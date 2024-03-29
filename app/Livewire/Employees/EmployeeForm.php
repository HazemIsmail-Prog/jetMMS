<?php

namespace App\Livewire\Employees;

use App\Livewire\Forms\EmployeeForm as FormsEmployeeForm;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class EmployeeForm extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Employee $employee;
    public $selectedUser;
    public FormsEmployeeForm $form;


    #[On('showEmployeeFormModal')]
    public function show(Employee $employee)
    {
        $this->reset('selectedUser');
        $this->form->reset();
        $this->employee = $employee;
        $this->form->fill($this->employee);
        if ($this->employee->id) {
            $this->selectedUser = User::find($this->employee->user_id);
        }
        $this->modalTitle = $this->employee->id ? __('messages.edit_employee_details') : __('messages.add_employee');
        $this->showModal = true;
    }

    #[Computed()]
    public function users()
    {
        return User::query()
            ->whereDoesntHave('employee')
            ->select('id', 'name_en', 'name_ar')
            ->get();
    }

    #[Computed()]
    public function companies()
    {
        return Company::query()
            ->select('id', 'name_en', 'name_ar')
            ->get();
    }

    public function updated($key, $val)
    {
        if ($key === 'form.user_id') {
            if ($val == '') {
                $this->selectedUser = null;
            } else {
                $this->selectedUser = User::find($val);
            }
        }
    }

    public function save()
    {
        $validated = $this->form->validate();
        Employee::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('employeesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.employees.employee-form');
    }
}
