<?php

namespace App\Livewire\Accounts;

use App\Models\Account;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AccountIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('accountsUpdated')]
    public function accounts()
    {
        return Account::query()
            ->where('level', 0)
            ->with('child_accounts', function($q){
                $q->withSum('voucher_details','debit');
                $q->with('child_accounts',function($q){
                    $q->withSum('voucher_details','debit');
                });
            })
            ->get();
    }

    public function delete(Account $account)
    {
        $account->delete();
    }

    public function render()
    {
        // dd($this->accounts);
        return view('livewire.accounts.account-index')->title(__('messages.accounts'));
    }
}
