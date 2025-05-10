<?php

namespace App\Livewire\PartInvoices;

use App\Livewire\Forms\PartInvoiceForm as FormsPartInvoiceForm;
use App\Models\PartInvoice;
use App\Models\Supplier;
use App\Models\Title;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class PartInvoiceForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public PartInvoice $part_invoice;

    public FormsPartInvoiceForm $form;

    #[Computed()]
    public function suppliers()
    {
        return Supplier::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    public function updated($key,$val) {
        if($key == 'form.invoice_amount' || $key == 'form.discount_amount'){
            $this->form->getCostAmount();
        }
    }

    #[Computed()]
    public function contacts()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->whereIn('title_id',Title::TECHNICIANS_GROUP)
            ->orderBy('name')
            ->get();
    }

    #[On('showPartInvoiceFormModal')]
    public function show(PartInvoice $part_invoice)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->part_invoice = $part_invoice;
        $this->modalTitle = $this->part_invoice->id ? __('messages.edit_part_invoice') . ' ' . $this->part_invoice->id : __('messages.add_part_invoice');
        $this->form->fill($this->part_invoice);
        if(!$this->form->id){
            // if the current time is 3:59 am, set the date to yesterday else set it to today
            if(now()->hour < 3 || (now()->hour == 3 && now()->minute < 59)){
                $this->form->date = today()->subDay()->format('Y-m-d');
            }else{
                $this->form->date = today()->format('Y-m-d');
            }
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('partInvoicesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.part-invoices.part-invoice-form');
    }
}
