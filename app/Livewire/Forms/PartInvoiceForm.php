<?php

namespace App\Livewire\Forms;

use App\Models\PartInvoice;
use Illuminate\Support\Facades\DB;
use Livewire\Form;

class PartInvoiceForm extends Form
{
    public $id;
    public $manual_id;
    public $date;
    public $supplier_id;
    public $contact_id;
    public float $invoice_amount = 0;
    public float $discount_amount = 0;
    public float $cost_amount = 0;
    public float $sales_amount = 0;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'manual_id' => 'nullable',
            'date' => 'required',
            'supplier_id' => 'required',
            'contact_id' => 'required',
            'invoice_amount' => 'required',
            'discount_amount' => 'required',
            'cost_amount' => 'required|numeric|gt:0',
            'sales_amount' => 'required',
        ];
    }



    public function getCostAmount() {
        $this->cost_amount = round(($this->invoice_amount ?? 0) - ($this->discount_amount ?? 0),3) ;
    }

    public function updateOrCreate() {
        $this->validate();
        DB::beginTransaction();
        try {
            PartInvoice::updateOrCreate(['id' => $this->id], $this->all()); // Observer Applied
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
