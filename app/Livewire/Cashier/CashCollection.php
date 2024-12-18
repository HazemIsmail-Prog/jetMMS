<?php

namespace App\Livewire\Cashier;

use App\Models\Department;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Title;
use App\Models\User;
use App\Services\CreateInvoicePaymentVoucher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CashCollection extends Component
{

    use WithPagination;

    public $filters;
    public $perPage = 10;

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
                'status' => '0',
                'start_created_at' => today()->format('Y-m-d'),
                'end_created_at' => today()->format('Y-m-d'),
                'department_id' => [],
                'technician_id' => [],
            ];
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', true)
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function technicians()
    {
        $settings = Setting::find(1);
        return User::query()

            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->orderBy('name')
            // ->whereHas('voucherDetails', function (Builder $q) {
            //     $q->whereHas('voucher', function (Builder $q) {
            //         $q->whereBetween('date', [$this->filters['start_created_at'], $this->filters['end_created_at']]);
            //     });
            // })

            // Cash Account
            ->withSum(['voucherDetails as cashAccountDebit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->cash_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->filters['start_created_at'], $this->filters['end_created_at']]);
                });
            }], 'debit')
            ->withSum(['voucherDetails as cashAccountCredit' => function (Builder $q) use ($settings) {
                $q->where('account_id', $settings->cash_account_id);
                $q->whereHas('voucher', function (Builder $q) {
                    $q->whereBetween('date', [$this->filters['start_created_at'], $this->filters['end_created_at']]);
                });
            }], 'credit')

            ->get();
    }

    #[Computed()]
    public function payments()
    {
        return Payment::query()
            ->with('invoice.order.technician.department')
            ->with('user')
            ->where('is_collected', $this->filters['status'])
            //TODO:: Enable following line after provide opening voucher
            // ->when($this->filters['status'] == '1',function(Builder $q){
            //     $q->whereHas('vouchers');
            // })
            ->where('method', 'cash')

            ->when($this->filters['department_id'], function (Builder $q) {
                $q->whereHas('invoice', function (Builder $q) {
                    $q->whereHas('order', function (Builder $q) {
                        $q->whereIn('department_id', $this->filters['department_id']);
                    });
                });
            })

            ->when($this->filters['technician_id'], function (Builder $q) {
                $q->whereHas('invoice', function (Builder $q) {
                    $q->whereHas('order', function (Builder $q) {
                        $q->whereIn('technician_id', $this->filters['technician_id']);
                    });
                });
            })

            ->when($this->filters['start_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '>=', $this->filters['start_created_at']);
            })

            ->when($this->filters['end_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '<=', $this->filters['end_created_at']);
            })

            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function technicianClicked($technician_id)
    {
        $this->filters['technician_id'] = [$technician_id];
    }

    public function collect_payment(Payment $payment)
    {
        if (!$payment->is_collected) {
            DB::beginTransaction();
            try {
                $payment->update([
                    'is_collected' => true,
                    'collected_by' => auth()->id(),
                ]);
                CreateInvoicePaymentVoucher::createCashPaymentVoucher($payment);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }

    public function uncollect_payment(Payment $payment)
    {
        if ($payment->is_collected) {
            DB::beginTransaction();
            try {
                $payment->update([
                    'is_collected' => false,
                    'collected_by' => null,
                ]);
                if ($payment->vouchers()->count() > 0) {
                    // must foreach to delete related voucher details via observer
                    foreach ($payment->vouchers as $voucher) {
                        $voucher->delete();
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }

    public function changeDate($newDate, Payment $payment)
    {
        $payment->update([
            'created_at' => $newDate,
            'updated_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.cashier.cash-collection')->title(__('messages.cash_collection'));
    }
}
