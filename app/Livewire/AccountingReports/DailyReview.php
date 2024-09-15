<?php

namespace App\Livewire\AccountingReports;

use App\Models\Department;
use App\Models\Setting;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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
    #[On('dateUpdated')]
    public function technicians()
    {

        $settings = Setting::find(1);

        // Subquery to calculate the total amount from invoice_details based on part type
        $invoiceDetailsTotals = DB::table('invoice_details')
            ->join('services', 'services.id', '=', 'invoice_details.service_id') // Join with services table
            ->select(
                'invoice_details.invoice_id',
                DB::raw('SUM(CASE WHEN services.type = "service" THEN invoice_details.quantity * invoice_details.price ELSE 0 END) as totalServiceAmount'),
                DB::raw('SUM(CASE WHEN services.type = "part" THEN invoice_details.quantity * invoice_details.price ELSE 0 END) as totalPartAmount')
            )
            ->groupBy('invoice_details.invoice_id');

        $invoicePartDetailsTotals = DB::table('invoice_part_details')
            ->select(
                'invoice_part_details.invoice_id',
                DB::raw('SUM(CASE WHEN invoice_part_details.type = "internal" THEN invoice_part_details.quantity * invoice_part_details.price ELSE 0 END) as totalInternalPartsAmount'),
                DB::raw('SUM(CASE WHEN invoice_part_details.type = "external" THEN invoice_part_details.quantity * invoice_part_details.price ELSE 0 END) as totalExternalPartsAmount')
            )
            ->groupBy('invoice_part_details.invoice_id');

        return User::query()

            ->join('orders', 'orders.technician_id', '=', 'users.id')
            ->join('invoices', function ($join) {
                $join->on('invoices.order_id', '=', 'orders.id')
                    ->whereNull('invoices.deleted_at')
                    ->whereDate('invoices.created_at', '>=', $this->start_date)
                    ->whereDate('invoices.created_at', '<=', $this->end_date);
            })
            ->leftJoinSub($invoiceDetailsTotals, 'invoice_details_totals', function ($join) {
                $join->on('invoice_details_totals.invoice_id', '=', 'invoices.id');
            })
            ->leftJoinSub($invoicePartDetailsTotals, 'invoice_parts_totals', function ($join) {
                $join->on('invoice_parts_totals.invoice_id', '=', 'invoices.id');
            })
            ->select(
                'users.id',
                'users.name_'.app()->getLocale(),
                'users.department_id',
                'users.title_id',
                DB::raw('COUNT(invoices.id) as invoicesCount'),
                DB::raw('SUM(invoice_details_totals.totalServiceAmount) as totalServiceAmount'),
                DB::raw('SUM(invoice_details_totals.totalPartAmount) as totalPartAmount'),


                DB::raw('SUM(invoice_parts_totals.totalInternalPartsAmount) as totalInternalPartsAmount'),
                DB::raw('SUM(invoice_parts_totals.totalExternalPartsAmount) as totalExternalPartsAmount'),


                DB::raw('SUM(invoices.discount) as discountSum'),
                DB::raw('SUM(invoices.delivery) as deliverySum'),
                DB::raw('SUM(invoice_details_totals.totalServiceAmount) + SUM(invoice_details_totals.totalPartAmount) + SUM(invoice_parts_totals.totalInternalPartsAmount) + SUM(invoice_parts_totals.totalExternalPartsAmount) + SUM(invoices.delivery) - SUM(invoices.discount) as grandTotal')
            )
            // ->where('users.id', 27)

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
            ->groupBy('users.id','users.department_id','users.title_id','users.name_'.app()->getLocale())
            ->get();
    }
    
    #[Computed()]
    #[On('dateUpdated')]
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
    #[On('dateUpdated')]
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
