<?php

namespace App\Livewire\Areas;

use App\Livewire\Forms\AreaForm as FormsAreaForm;
use App\Models\Area;
use Livewire\Attributes\On;
use Livewire\Component;

class AreaForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Area $area;

    public FormsAreaForm $form;

    #[On('showAreaFormModal')]
    public function show(Area $area)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->area = $area;
        $this->modalTitle = $this->area->id ? __('messages.edit_area') . ' ' . $this->area->name : __('messages.add_area');
        $this->form->fill($this->area);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('areasUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.areas.area-form');
    }
}
