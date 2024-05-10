<?php

namespace App\Livewire\AccountingReports;

use App\Models\Account;
use App\Models\Setting;
use App\Models\User;
use App\Models\VoucherDetail;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UsersReceivables extends Component
{
    public $date = '';
    public $receivables_accounts_ids = [];

    public function mount()
    {
        $this->date = today()
            ->subDay(1)
            ->format('Y-m-d');
    }

    #[Computed()]
    public function users()
    {
        return User::query()
            ->select('id','department_id','title_id','name_'.app()->getLocale())
            ->with('title:id,name_'.app()->getLocale())
            ->with('department:id,name_'.app()->getLocale())
            ->whereHas('voucherDetails', function (Builder $q) {
                $q->whereIn('account_id',$this->receivableAccounts->pluck('id'));
                // $q->whereHas('voucher', function (Builder $q) {
                //     $q->where('date', $this->date);
                // });
            })
            ->orderBy('department_id')
            ->orderBy('title_id')
            ->orderBy('name_' . app()->getLocale())
            ->get();
    }

    #[Computed()]
    public function receivableAccounts()
    {
        return Account::query()
            ->where('account_id', Account::RECEIVABLE_ACCOUNTS_PARENT_ID)
            ->whereHas('voucher_details', function (Builder $q) {
                // $q->whereHas('voucher', function (Builder $q) {
                //     $q->where('date', $this->date);
                // });
            })
            ->get();
    }

    #[Computed()]
    public function voucherDetails()
    {
        return VoucherDetail::query()
            ->whereIn('account_id', $this->receivableAccounts->pluck('id'))
            ->whereNotNull('user_id')
            // ->whereHas('voucher', function (Builder $q) {
            //     $q->where('date', $this->date);
            // })
            ->get();
    }

    public function render()
    {
        return view('livewire.accounting-reports.users-receivables')->title(__('messages.users_receivables'));
    }
}
