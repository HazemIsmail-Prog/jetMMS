<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Attributes\On;
use Livewire\Component;

class EmployeeView extends Component
{
    public Employee $employee;
    public $showModal = false;
    public $modalTitle = '';

    #[On('showEmployeeViewModal')]
    public function show(Employee $employee)
    {
        $this->employee = $employee;
        $this->modalTitle = $this->employee->id ? __('messages.edit_employee_details') : __('messages.add_employee');
        $this->showModal = true;
    }


    
    public function render()
    {
        return view('livewire.employees.employee-view');
    }
}
