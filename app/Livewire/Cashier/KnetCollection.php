<?php

namespace App\Livewire\Cashier;

use App\Models\Payment;
use App\Services\CreateInvoicePaymentVoucher;
use App\Services\CreatePaymentVoucher;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class KnetCollection extends Component
{
    #[Computed()]
    public function unCollectedPayments()
    {
        return Payment::query()
        ->with('invoice')
        ->with('user')
            ->where('is_collected', false)
            ->where('method', 'knet')
            ->get();
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
        return view('livewire.cashier.knet-collection');
    }
}
