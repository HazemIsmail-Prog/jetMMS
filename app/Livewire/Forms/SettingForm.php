<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SettingForm extends Form
{
    public $logo;
    public $favicon;
    public $phone;
    public $fax;
    public $address_ar;
    public $address_en;
    public $knet_tax;
    public $cash_account_id;
    public $bank_account_id;
    public $bank_charges_account_id;
    public $receivables_account_id;
    public $internal_parts_account_id;

    public function rules()
    {
        return [
            'logo' => 'nullable',
            'favicon' => 'nullable',
            'phone' => 'nullable',
            'fax' => 'nullable',
            'address_ar' => 'nullable',
            'address_en' => 'nullable',
            'knet_tax' => 'required',
            'cash_account_id' => 'required',
            'bank_account_id' => 'required',
            'bank_charges_account_id' => 'required',
            'receivables_account_id' => 'required',
            'internal_parts_account_id' => 'required',
        ];
    }
}
