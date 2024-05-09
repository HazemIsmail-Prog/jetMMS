<?php

namespace App\Livewire\AccountingReports;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\VoucherDetail;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CollectionStatement extends Component
{
    public $date = '';

    public function mount()
    {
        $this->date = today()
        ->subDay(1)
        ->format('Y-m-d');
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', 1)
            ->with('technicians',function($q){
                $q->with('title:id,name_en,name_ar');
                $q->whereHas('voucherDetails',function(Builder $q){
                    $q->whereHas('voucher',function(Builder $q){
                        $q->where('date',$this->date);
                    });
                });
            })
            ->get();
    }

    #[Computed()]
    public function invoices()
    {
        return Invoice::query()
            ->whereDate('created_at', $this->date)
            ->with('order:id,department_id,technician_id')
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
    public function bankTransactions()
    {
        return $this->voucherDetails->where('account_id',Setting::find(1)->bank_account_id);
    }

    #[Computed()]
    public function bankChargesTransactions()
    {
        return $this->voucherDetails->where('account_id',Setting::find(1)->bank_charges_account_id);
    }

    #[Computed()]
    public function cashTransactions()
    {
        return $this->voucherDetails->where('account_id',Setting::find(1)->cash_account_id);
    }

    public function render()
    {
        return view('livewire.accounting-reports.collection-statement')->title(__('messages.collection_statement'));
    }
}
