<?php

namespace App\Livewire\Accounts;

use App\Livewire\Forms\AccountForm as FormsAccountForm;
use App\Models\Account;
use Livewire\Attributes\On;
use Livewire\Component;

class AccountForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Account $account;

    public FormsAccountForm $form;

    #[On('showAccountFormModal')]
    public function show(Account $account ,Account $parentAccount)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->account = $account;
        $this->modalTitle = $this->account->id ? __('messages.edit_account') . ' ' . $this->account->name : __('messages.add_account');
        $this->form->fill($this->account);
        if($parentAccount->id){
            $this->form->account_id = $parentAccount->id;
            $this->form->level = $parentAccount->level + 1;
            $this->form->type = $parentAccount->type;
        }

    }

    public function save()
    {
        $validated = $this->form->validate();
        Account::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('accountsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.accounts.account-form');
    }
}
