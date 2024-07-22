<?php

namespace App\Livewire\Quotations;

use App\Livewire\Forms\QuotationForm as FormsQuotationForm;
use App\Models\Quotation;
use Livewire\Attributes\On;
use Livewire\Component;

class QuotationForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Quotation $quotation;
    public FormsQuotationForm $form;

    #[On('showQuotationFormModal')]
    public function show(Quotation $quotation)
    {
        $this->reset('quotation');
        $this->form->resetErrorBag();
        $this->form->reset();
        $this->showModal = true;
        $this->quotation = $quotation;
        if (!$this->quotation->id) {
            //create
            $this->modalTitle = __('messages.add_quotation');
        } else {
            //edit
            $this->modalTitle = __('messages.edit_quotation');
            $this->form->fill($this->quotation);
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->showModal = false;
        $this->dispatch('quotationsUpdated');
    }

    public function render()
    {
        return view('livewire.quotations.quotation-form');
    }
}
