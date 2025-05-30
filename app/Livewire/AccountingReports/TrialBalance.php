<?php

namespace App\Livewire\AccountingReports;

use App\Exports\TrialBalanceExport;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;


class TrialBalance extends Component
{
    public $filters;

    public function mount()
    {
        $this->filters =
            [
                'start_date' => today()->startOfYear()->format('Y-m-d'),
                'end_date' => today()->format('Y-m-d'),
            ];
    }

    public function excel()
    {
        return Excel::download(new TrialBalanceExport('livewire.accounting-reports.excel.trial-balance', 'Trial Balance from ' . $this->filters['start_date'] . ' to ' . $this->filters['end_date'], $this->accounts()), 'Trial Balance from ' . $this->filters['start_date'] . ' to ' . $this->filters['end_date'] . '.xlsx');  //Excel
    }

    #[Computed()]
    public function accounts()
    {
        return Account::query()

            // Opening
            ->withSum(['voucher_details as opening_debit' => function (Builder $q) {
                $q->whereHas('voucher', function (Builder $q) {
                    $q->where('date', '<', $this->filters['start_date']);
                });
            }], 'debit')
            ->withSum(['voucher_details as opening_credit' => function (Builder $q) {
                $q->whereHas('voucher', function (Builder $q) {
                    $q->where('date', '<', $this->filters['start_date']);
                });
            }], 'credit')

            // transactions
            ->withSum(['voucher_details as transactions_debit' => function (Builder $q) {
                $q->whereHas('voucher', function (Builder $q) {
                    $q->where('date', '>=', $this->filters['start_date']);
                    $q->where('date', '<=', $this->filters['end_date']);
                });
            }], 'debit')
            ->withSum(['voucher_details as transactions_credit' => function (Builder $q) {
                $q->whereHas('voucher', function (Builder $q) {
                    $q->where('date', '>=', $this->filters['start_date']);
                    $q->where('date', '<=', $this->filters['end_date']);
                });
            }], 'credit')


            ->where('level', 3)
            ->orderBy('account_id')
            ->get()
        ;
    }

    public function render()
    {
        return view('livewire.accounting-reports.trial-balance')->title(__('messages.trial_balance'));
    }
}
