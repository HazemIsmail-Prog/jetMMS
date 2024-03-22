<?php

namespace App\Livewire\PartInvoices;

use App\Livewire\Forms\PartInvoiceForm as FormsPartInvoiceForm;
use App\Models\PartInvoice;
use App\Models\Supplier;
use App\Models\Title;
use App\Models\User;
use App\Services\CreatePartInvoiceVoucher;
use Illuminate\Support\Facades\DB;
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
    }

    public function save()
    {
        $validated = $this->form->validate();

        DB::beginTransaction();
        try {
            $part_invoice = PartInvoice::updateOrCreate(['id' => $validated['id']], $validated);
            if (!$validated['id']) {
                // create
                CreatePartInvoiceVoucher::createVoucher($part_invoice);
            }
            // TODO: Edit
            DB::commit();
            $this->dispatch('partInvoicesUpdated');
            $this->showModal = false;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.part-invoices.part-invoice-form');
    }
}
