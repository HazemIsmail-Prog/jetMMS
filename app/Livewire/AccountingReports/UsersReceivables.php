<?php

namespace App\Livewire\AccountingReports;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UsersReceivables extends Component
{

    public $date = '';

    public function mount()
    {
        $this->date = today()
            ->subDay(1)
            ->format('Y-m-d');
    }

    #[Computed()]
    public function users()
    {
        $users =  User::query()
            ->select('id', 'department_id', 'title_id', 'name_' . app()->getLocale())
            ->with('title:id,name_' . app()->getLocale())
            ->with('department:id,name_' . app()->getLocale())
            ->withWhereHas('voucherDetails', function ($q) {
                $q->whereIn('account_id', $this->receivableAccounts->pluck('id'));
                // $q->whereHas('voucher', function (Builder $q) {
                //     $q->where('date', $this->date);
                // });
            })
            ->withSum(
                ['voucherDetails as debit_total' => fn ($query) => $query->whereIn('account_id', $this->receivableAccounts->pluck('id'))],
                'debit'
            )
            ->withSum(
                ['voucherDetails as credit_total' => fn ($query) => $query->whereIn('account_id', $this->receivableAccounts->pluck('id'))],
                'credit'
            )

            ->orderBy('department_id')
            ->orderBy('title_id')
            ->orderBy('name_' . app()->getLocale())
            ->get();


        foreach ($this->receivableAccounts as $account) {
            $users->loadSum(
                ['voucherDetails as debit_sum_of_account_' . $account->id => fn ($query) => $query->where('account_id', $account->id)],
                'debit'
            );
            $users->loadSum(
                ['voucherDetails as credit_sum_of_account_' . $account->id => fn ($query) => $query->where('account_id', $account->id)],
                'credit'
            );
        }

        return $users;
    }

    #[Computed()]
    public function receivableAccounts()
    {
        return Account::query()
            ->select('id','account_id','name_'.app()->getLocale())
            ->where('account_id', Account::RECEIVABLE_ACCOUNTS_PARENT_ID)
            ->whereHas('voucher_details', function (Builder $q) {
                $q->whereNotNull('user_id');
                // $q->whereHas('voucher', function (Builder $q) {
                //     $q->where('date', $this->date);
                // });
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.accounting-reports.users-receivables')->title(__('messages.users_receivables'));
    }
}
