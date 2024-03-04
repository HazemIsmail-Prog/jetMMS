<?php

namespace App\Livewire\Suppliers;

use App\Livewire\Forms\SupplierForm as FormsSupplierForm;
use App\Models\Account;
use App\Models\Supplier;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class SupplierForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Supplier $supplier;

    public FormsSupplierForm $form;

    #[Computed()]
    public function accounts()
    {
        return Account::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name', 'account_id')
            ->orderBy('name')
            ->where('level', 3)
            ->with('parent')
            ->get();
    }

    #[On('showSupplierFormModal')]
    public function show(Supplier $supplier)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->supplier = $supplier;
        $this->modalTitle = $this->supplier->id ? __('messages.edit_supplier') . ' ' . $this->supplier->name : __('messages.add_supplier');
        $this->form->fill($this->supplier);
    }

    public function save()
    {
        $validated = $this->form->validate();
        Supplier::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('suppliersUpdated');
        $this->showModal = false;
    }


    public function render()
    {
        return view('livewire.suppliers.supplier-form');
    }
}
