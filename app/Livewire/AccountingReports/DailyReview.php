<?php

namespace App\Livewire\AccountingReports;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DailyReview extends Component
{
    public $start_date = '';
    public $end_date = '';

    public function mount()
    {
        $this->start_date = today()->subDay(1)->format('Y-m-d');
        $this->end_date = today()->subDay(1)->format('Y-m-d');
    }

    #[Computed()]
    public function technicians()
    {
        $settings = Setting::find(1);
        return User::query()

            ->select("id", "department_id", "title_id")
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->whereHas('voucherDetails', function (Builder $q) {
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            })

            // Part Difference
            ->withSum(['voucherDetails as PartDifferenceDebit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->internal_parts_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as PartDifferenceCredit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->internal_parts_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

            // Service Cost Center
            ->withSum(['voucherDetails as servicesCostCenterDebit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->where('cost_center_id', 1);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as servicesCostCenterCredit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->where('cost_center_id', 1);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

            // Parts Cost Center
            ->withSum(['voucherDetails as partsCostCenterDebit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->where('cost_center_id', 2);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as partsCostCenterCredit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->where('cost_center_id', 2);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

            // Delivery Cost Center
            ->withSum(['voucherDetails as deliveryCostCenterDebit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->where('cost_center_id', 3);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as deliveryCostCenterCredit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->where('cost_center_id', 3);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

            // Income Account
            ->withSum(['voucherDetails as incomeAccountDebit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as incomeAccountCredit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('income_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')


            // Cost Account
            ->withSum(['voucherDetails as costAccountDebit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('cost_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as costAccountCredit' => function (Builder $q) {
                $q->where('account_id', function ($query) {
                    $query->select('cost_account_id')
                        ->from('departments')
                        ->whereColumn('departments.id', 'users.department_id');
                });
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', 1)
            ->with('technicians', function ($q) {
                $q->with('title:id,name_en,name_ar');
                $q->whereHas('voucherDetails', function (Builder $q) {
                    $q->whereHas('voucher', function (Builder $q) {
                        $q->whereBetween('date', [$this->start_date, $this->end_date]);
                    });
                });
            })
            ->get();
    }

    #[Computed()]
    public function invoices()
    {
        return Invoice::query()
            ->whereDate('created_at','>=' ,$this->start_date)
            ->whereDate('created_at','<=' ,$this->end_date)
            ->with('order:id,department_id,technician_id')
            ->withSum('invoice_details as servicesAmountSum', DB::raw('quantity * price'))

            ->withSum(['invoice_details as invoiceDetailsPartsAmountSum' => function ($q) {
                $q->whereHas('service', function ($q) {
                    $q->where('type', 'part');
                });
            }], DB::raw('quantity * price'))

            ->withSum(['invoice_part_details as internalPartsAmountSum' => function ($q) {
                $q->where('type', 'internal');
            }], DB::raw('quantity * price'))

            ->withSum(['invoice_part_details as externalPartsAmountSum' => function ($q) {
                $q->where('type', 'external');
            }], DB::raw('quantity * price'))

            ->get();
    }

    #[Computed()]
    public function titles()
    {
        return Title::query()
            ->whereIn('id', Title::TECHNICIANS_GROUP)
            ->get();
    }

    public function render()
    {
        return view('livewire.accounting-reports.daily-review')->title(__('messages.daily_review'));
    }
}
