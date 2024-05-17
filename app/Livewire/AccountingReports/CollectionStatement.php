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

class CollectionStatement extends Component
{
    public $start_date = '';
    public $end_date = '';

    public function mount()
    {
        $this->start_date = today()->subDay(1)->format('Y-m-d');
        $this->end_date = today()->subDay(1)->format('Y-m-d');
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
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
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

            ->withSum(['payments as cashAmountSum' => function ($q) {
                $q->where('method', 'cash');
            }], 'amount')

            ->withSum(['payments as knetAmountSum' => function ($q) {
                $q->where('method', 'knet');
            }], 'amount')

            ->get();
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

            // Bank Account
            ->withSum(['voucherDetails as bankAccountDebit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->bank_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as bankAccountCredit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->bank_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

            // Bank Charges Account
            ->withSum(['voucherDetails as bankChargesAccountDebit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->bank_charges_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as bankChargesAccountCredit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->bank_charges_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

            // Cash Account
            ->withSum(['voucherDetails as cashAccountDebit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->cash_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as cashAccountCredit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->cash_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->start_date, $this->end_date]);
                });
            }], 'credit')

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
        return view('livewire.accounting-reports.collection-statement')->title(__('messages.collection_statement'));
    }
}
