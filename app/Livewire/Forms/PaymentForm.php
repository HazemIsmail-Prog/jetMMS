<?php

namespace App\Livewire\Forms;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Form;

class PaymentForm extends Form
{
    public $id;
    public $amount;
    public $method;
    public $knet_ref_number;
    public $user_id;
    public $invoice;
    public $invoice_id;
    public $is_collected = false;

    public function rules()
    {
        return [
            'amount' => 'required',
            'method' => 'required',
            'knet_ref_number' => 'nullable|required_if:method,knet|numeric|digits:6',
            'invoice_id' => 'required',
        ];
    }

    public function setData(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->invoice_id = $invoice->id;
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->user_id = auth()->id();

        DB::beginTransaction();
        try {
            Payment::updateOrCreate(['id' => $this->id], $this->except('invoice'));
            $this->invoice->update(['payment_status' => $this->invoice->computePaymentStatus()]);
            DB::commit();
            $this->reset();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

    }
}
