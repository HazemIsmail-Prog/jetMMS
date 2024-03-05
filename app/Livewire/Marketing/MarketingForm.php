<?php

namespace App\Livewire\Marketing;

use App\Livewire\Forms\MarketingForm as FormsMarketingForm;
use App\Models\Marketing;
use Livewire\Attributes\On;
use Livewire\Component;

class MarketingForm extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Marketing $marketing;
    public FormsMarketingForm $form;

    #[On('showMarketingFormModal')]
    public function show(Marketing $marketing)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->marketing = $marketing;
        $this->modalTitle = $this->marketing->id ? __('messages.edit_marketing') . $this->marketing->id : __('messages.add_marketing');
        $this->form->fill($this->marketing);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('marketingsUpdated');
        $this->form->reset();
        $this->showModal = false;
    }
    
    public function render()
    {
        return view('livewire.marketing.marketing-form');
    }
}
