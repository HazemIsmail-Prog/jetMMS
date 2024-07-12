<?php

namespace App\Livewire\Contracts;

use App\Livewire\Forms\ContractForm as FormsContractForm;
use App\Models\Contract;
use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Component;

class ContractForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Customer $customer;
    public Contract $contract;
    public FormsContractForm $form;

    #[On('showContractFormModal')]
    public function show(Customer $customer, Contract $contract)
    {
        $this->reset('customer', 'contract');
        $this->form->resetErrorBag();
        $this->form->reset();
        $this->showModal = true;
        $this->contract = $contract;
        $this->customer = $customer;
        if (!$this->contract->id) {
            //create
            $this->modalTitle = __('messages.add_contract');
            $this->form->customer_id = $this->customer->id;
            $this->form->address_id = $this->customer->addresses->count() == 1 ? $this->customer->addresses()->first()->id : null;
        } else {
            //edit
            $this->modalTitle = __('messages.edit_contract');
            $this->form->fill($this->contract);
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->showModal = false;
        $this->dispatch('contractsUpdated');
    }

    public function render()
    {
        return view('livewire.contracts.contract-form');
    }
}
