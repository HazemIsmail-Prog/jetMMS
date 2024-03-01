<?php

namespace App\Livewire\Orders;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaymentForm extends Component
{
    public Invoice $invoice;
    public $amount;
    public $method;
    public $knet_ref_number;
    public $showForm = false;

    public function rules()
    {
        return [
            'amount' => 'required',
            'method' => 'required',
            'knet_ref_number' => 'nullable|required_if:method,knet|numeric|digits:6',
        ];
    }

    public function updatedMethod()
    {
        $this->knet_ref_number = null;
    }

    public function showPaymentForm()
    {
        $this->showForm = true;
    }

    public function hidePaymentForm()
    {
        $this->reset();
    }

    public function save_payment()
    {

        $this->validate();
        DB::beginTransaction();
        try {
            Payment::create([
                'invoice_id' => $this->invoice->id,
                'amount' => $this->amount,
                'method' => $this->method,
                'user_id' => auth()->id(),
                'is_collected' => false,
                'knet_ref_number' => $this->knet_ref_number, //044806
            ]);

            $this->invoice->update(['payment_status' => $this->invoice->computePaymentStatus()]);


            DB::commit();

            $this->dispatch('paymentReceived');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.orders.payment-form');
    }
}
