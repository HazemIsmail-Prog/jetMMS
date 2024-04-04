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
    public $cost_amount;
    public $sales_amount;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'manual_id' => 'nullable',
            'date' => 'required',
            'supplier_id' => 'required',
            'contact_id' => 'required',
            'cost_amount' => 'required',
            'sales_amount' => 'required',
        ];
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
