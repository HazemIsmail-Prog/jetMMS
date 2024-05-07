<?php

namespace App\Livewire\AccountingReports;

use App\Models\Account;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\VoucherDetail;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DailyReview extends Component
{
    public $account_id = [];
    public $cost_center_id = [];
    public $department_id = [];
    public $contact_id = [];
    public $date = '';

    public function mount()
    {
        $this->date = today()
        ->subDay(7)
        ->format('Y-m-d');
    }

    #[Computed()]
    public function accountsVoucherDetails()
    {
        return Account::query()
            ->where('level', 3)
            ->whereIn('id', $this->account_id)

            //Get openining sum of Debit before start Date
            ->withSum(['voucher_details' => function ($q) {
                $q->whereHas('voucher', function ($q) {
                    $q->where('date', '<', $this->date);
                });
            }], 'debit')

            //Get openining sum of credit before start Date
            ->withSum(['voucher_details' => function ($q) {
                $q->whereHas('voucher', function ($q) {
                    $q->where('date', '<', $this->date);
                });
            }], 'credit')

            ->withWhereHas('voucher_details', function ($q) {
                $q->withWhereHas('voucher', function ($q) {
                    $q->where('date', [$this->date]);
                });
            })
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', 1)
            ->with('technicians',function($q){
                $q->with('title');
                $q->whereHas('voucherDetails',function(Builder $q){
                    $q->whereHas('voucher',function(Builder $q){
                        $q->where('date',$this->date);
                    });
                });
            })
            // ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name','inc')
            // ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function invoices()
    {
        return Invoice::query()
            ->whereDate('created_at', $this->date)
            ->with('order')
            ->get();
    }

    #[Computed()]
    public function voucherDetails()
    {
        return VoucherDetail::query()
            ->whereHas('voucher', function (Builder $q) {
                $q->where('date', $this->date);
            })
            ->get();
    }

    #[Computed()]
    public function partsAccountTransactions()
    {
        return VoucherDetail::query()
        ->where('account_id',Setting::find(1)->internal_parts_account_id)
            ->whereHas('voucher', function (Builder $q) {
                $q->where('date', $this->date);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.accounting-reports.daily-review')->title(__('messages.daily_review'));
    }
}
