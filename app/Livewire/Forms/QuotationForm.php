<?php

namespace App\Livewire\Forms;

use App\Models\Quotation;
use Livewire\Attributes\Validate;
use Livewire\Form;

class QuotationForm extends Form
{
    public $id;
    public $user_id;
    public $quotation_number;
    public $customer_name;
    public $amount;
    public $description;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'user_id' => 'required',
            'quotation_number' => 'required',
            'customer_name' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->user_id = auth()->id();
        $this->validate();
        Quotation::updateOrCreate(['id' => $this->id], $this->all());
    }
}
