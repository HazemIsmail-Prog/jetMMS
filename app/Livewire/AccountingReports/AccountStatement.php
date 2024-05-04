<?php

namespace App\Livewire\AccountingReports;

use App\Models\Account;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AccountStatement extends Component
{

    public $account_id = [];
    public $cost_center_id = [];
    public $department_id = [];
    public $contact_id = [];
    public $start_date = '';
    public $end_date = '';

    public function mount()
    {
        $this->start_date = today()
            ->subDay(1)
            ->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
    }

    public function updatedDepartmentId($val) {
        $this->contact_id = User::whereIn('department_id',$this->department_id)->pluck('id');
    }

    #[Computed()]
    public function openning()
    {
        $debit = $this->accountsVoucherDetails()->first()->voucher_details_sum_debit;
        $credit = $this->accountsVoucherDetails()->first()->voucher_details_sum_credit;
        $type = $this->accountsVoucherDetails()->first()->type;
        $openning = [
            'debit' => $debit,
            'credit' => $credit,
            'type' => $type,
            'balance' => $type == 'debit' ? $debit - $credit : $credit - $debit,
        ];

        return $openning;
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
                    $q->where('date', '<', $this->start_date);
                });
            }], 'debit')

            //Get openining sum of credit before start Date
            ->withSum(['voucher_details' => function ($q) {
                $q->whereHas('voucher', function ($q) {
                    $q->where('date', '<', $this->start_date);
                });
            }], 'credit')

            ->withWhereHas('voucher_details', function ($q) {
                $q->withWhereHas('voucher', function ($q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
                $q->with('contact');
                $q->with('cost_center');
                $q->when($this->cost_center_id, function ($q) {
                    $q->whereIn('cost_center_id', $this->cost_center_id);
                });
                $q->when($this->contact_id, function ($q) {
                    $q->whereIn('user_id', $this->contact_id);
                });
            })
            ->get();
    }
    #[Computed()]
    public function accounts()
    {
        return Account::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name_' . app()->getLocale())
            ->where('level', 3)
            ->whereHas('voucher_details')
            ->get();
    }
    #[Computed()]
    public function cost_centers()
    {
        return CostCenter::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function contacts()
    {
        return User::query()
            ->whereHas('voucher_details')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', 1)
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }



    public function render()
    {
        return view('livewire.accounting-reports.account-statement')->title(__('messages.account_statement'));
    }
}
