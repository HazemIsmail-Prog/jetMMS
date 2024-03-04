<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
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
}
