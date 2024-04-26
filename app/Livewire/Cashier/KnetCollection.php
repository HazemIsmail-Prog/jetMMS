<?php

namespace App\Livewire\Cashier;

use App\Models\Payment;
use App\Models\Title;
use App\Models\User;
use App\Services\CreateInvoicePaymentVoucher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class KnetCollection extends Component
{

    public $filters;
    public $perPage = 10;


    public function mount()
    {
        $this->filters =
            [
                'start_created_at' => '',
                'end_created_at' => '',
                'technician_id' => [],
            ];
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function unCollectedPayments()
    {
        return Payment::query()
            ->with('invoice.order.technician')
            ->with('user')
            ->where('is_collected', false)
            ->where('method', 'knet')
            ->when($this->filters['technician_id'], function (Builder $q) {
                $q->whereHas('invoice',function(Builder $q){
                    $q->whereHas('order',function(Builder $q){
                        $q->whereIn('technician_id',$this->filters['technician_id']);
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

    public function technicianClicked($technician_id) {
        $this->filters['technician_id'] = [$technician_id];
    }

    public function collect_payment(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->update([
                'is_collected' => true,
                'collected_by' => auth()->id(),
            ]);
            CreateInvoicePaymentVoucher::createKnetPaymentVoucher($payment);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.cashier.knet-collection')->title(__('messages.knet_collection'));
    }
}
