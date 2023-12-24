<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;

class EmployeeView extends Component
{
    public Employee $employee;
    
    public function render()
    {
        return view('livewire.employees.employee-view');
    }
}
