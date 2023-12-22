<?php

namespace App\Livewire\Shifts;

use App\Livewire\Forms\ShiftForm as FormsShiftForm;
use App\Models\Shift;
use Livewire\Attributes\On;
use Livewire\Component;

class ShiftForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Shift $shift;

    public FormsShiftForm $form;

    #[On('showShiftFormModal')]
    public function show(Shift $shift)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->shift = $shift;
        $this->modalTitle = $this->shift->id ? __('messages.edit') . ' ' . $this->shift->name : __('messages.add_shift');
        $this->form->fill($this->shift);
    }

    public function save()
    {
        $validated = $this->form->validate();
        Shift::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('shiftsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.shifts.shift-form');
    }
}
