<?php

namespace App\Livewire\Titles;

use App\Livewire\Forms\TitleForm as FormsTitleForm;
use App\Models\Title;
use Livewire\Attributes\On;
use Livewire\Component;

class TitleForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Title $title;

    public FormsTitleForm $form;

    #[On('showTitleFormModal')]
    public function show(Title $title)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->title = $title;
        $this->modalTitle = $this->title->id ? __('messages.edit_title') . ' ' . $this->title->name : __('messages.add_title');
        $this->form->fill($this->title);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('titlesUpdated');
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.titles.title-form');
    }
}
