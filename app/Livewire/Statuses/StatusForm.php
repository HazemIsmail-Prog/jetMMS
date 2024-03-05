<?php

namespace App\Livewire\Statuses;

use App\Livewire\Forms\StatusForm as FormsStatusForm;
use App\Models\Status;
use Livewire\Attributes\On;
use Livewire\Component;

class StatusForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Status $status;

    public FormsStatusForm $form;

    #[On('showStatusFormModal')]
    public function show(Status $status)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->status = $status;
        $this->modalTitle = $this->status->id ? __('messages.edit_status') . ' ' . $this->status->name : __('messages.add_status');
        $this->form->fill($this->status);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('statusesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.statuses.status-form');
    }
}
