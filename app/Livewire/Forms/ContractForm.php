<?php

namespace App\Livewire\Forms;

use App\Models\Contract;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ContractForm extends Form
{
    public $id;
    public $customer_id;
    public $user_id;
    public $address_id;

    public $contract_type;
    public $contract_date;
    public $contract_duration;
    public $contract_value;
    public $contract_expiration_date;
    public $contract_number;
    public $building_type;
    public $units_count;
    public $central_count;
    public $collected_amount;
    public $notes;
    public bool $active = true;
    public bool $sp_included = false;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'customer_id' => 'required',
            'user_id' => 'required',
            'address_id' => 'required',

            'contract_type' => 'required',
            'contract_date' => 'required',
            'contract_duration' => 'required',
            'contract_value' => 'required',
            'contract_expiration_date' => 'nullable',
            'contract_number' => 'required',
            'building_type' => 'required',
            'units_count' => 'nullable',
            'central_count' => 'nullable',
            'collected_amount' => 'nullable',
            'notes' => 'nullable',
            'active' => 'required',
            'sp_included' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->user_id = auth()->id();
        $this->validate();
        Contract::updateOrCreate(['id' => $this->id], $this->all());
    }
}
