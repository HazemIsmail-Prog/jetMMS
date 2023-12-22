<?php

namespace App\Livewire\Departments;

use App\Livewire\Forms\DepartmentForm as FormsDepartmentForm;
use App\Models\Department;
use Livewire\Attributes\On;
use Livewire\Component;

class DepartmentForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Department $department;

    public FormsDepartmentForm $form;

    #[On('showDepartmentFormModal')]
    public function show(Department $department)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->department = $department;
        $this->modalTitle = $this->department->id ? __('messages.edit_department') . ' ' . $this->department->name : __('messages.add_department');
        $this->form->fill($this->department);
    }

    public function save()
    {
        $validated = $this->form->validate();
        Department::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('departmentsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.departments.department-form');
    }
}
