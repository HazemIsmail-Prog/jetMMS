<?php

namespace App\Livewire\AccountingReports;

use App\Models\Account;
use App\Models\Department;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class UsersReceivables extends Component
{

    use WithPagination;

    public $filters;
    public $perPage = 100;

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->filters =
            [
                'user_id' => [],
                'title_id' => [],
                'department_id' => [],
                'start_date' => '',
                'end_date' => '',
            ];
    }

    #[Computed()]
    public function receivableAccounts()
    {
        return Account::query()
            ->select('id', 'account_id', 'name_' . app()->getLocale())
            ->where('account_id', Account::RECEIVABLE_ACCOUNTS_PARENT_ID)
            ->whereHas('voucher_details', function (Builder $q) {
                $q->whereNotNull('user_id');
            })
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->whereHas('users', function (Builder $q) {
                $q->whereHas('voucherDetails');
            })
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function titles()
    {
        return Title::query()
            ->whereHas('users', function (Builder $q) {
                $q->whereHas('voucherDetails');
            })
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function users_filter_list()
    {
        return User::query()
            ->whereHas('voucherDetails')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function users()
    {
        $users = User::query()
            ->select('id', 'department_id', 'title_id', 'name_' . app()->getLocale())
            ->with('title:id,name_' . app()->getLocale())
            ->with('department:id,name_' . app()->getLocale())
            // ->whereHas('voucherDetails', function (Builder $q) {
            //     $this->applyVoucherDetailsFilters($q);
            // })
            ->withSum(['voucherDetails as debit_total'=> function (Builder $q) {
                $this->applyVoucherDetailsFilters($q);
            }],'debit')
            ->withSum(['voucherDetails as credit_total'=> function (Builder $q) {
                $this->applyVoucherDetailsFilters($q);
            }],'credit')
            ->when($this->filters['user_id'], function (Builder $q) {
                $q->whereIn('id', $this->filters['user_id']);
            })
            ->when($this->filters['title_id'], function (Builder $q) {
                $q->whereHas('title', function (Builder $q) {
                    $q->whereIn('id', $this->filters['title_id']);
                });
            })
            ->when($this->filters['department_id'], function (Builder $q) {
                $q->whereHas('department', function (Builder $q) {
                    $q->whereIn('id', $this->filters['department_id']);
                });
            })
            ->orderBy('department_id')
            ->orderBy('title_id')
            ->orderBy('name_' . app()->getLocale())
            ->paginate($this->perPage);

        foreach ($this->receivableAccounts as $account) {
            $this->loadVoucherDetailsSumsForAccount($users, $account);
        }
        
        return $users;
    }

    public function applyVoucherDetailsFilters(Builder $q)
    {
            $q->whereIn('account_id', $this->receivableAccounts->pluck('id'));
            $q->when($this->filters['start_date'], function (Builder $q) {
                $q->whereHas('voucher', function (Builder $q) {
                    $q->where('date', '>=', $this->filters['start_date']);
                });
            });
            $q->when($this->filters['end_date'], function (Builder $q) {
                $q->whereHas('voucher', function (Builder $q) {
                    $q->where('date', '<=', $this->filters['end_date']);
                });
            });
    }

    protected function loadVoucherDetailsSumsForAccount($users, $account)
    {
        $users->loadSum(
            ['voucherDetails as debit_sum_of_account_' . $account->id =>
            function (Builder $q) use ($account) {
                $q->where('account_id', $account->id);
                $this->applyVoucherDetailsFilters($q);
            }],
            'debit'
        );
    
        $users->loadSum(
            ['voucherDetails as credit_sum_of_account_' . $account->id =>
            function (Builder $q) use ($account) {
                $q->where('account_id', $account->id);
                $this->applyVoucherDetailsFilters($q);
            }],
            'credit'
        );
    }

    public function render()
    {
        return view('livewire.accounting-reports.users-receivables')->title(__('messages.users_receivables'));
    }
}
