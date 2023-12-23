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

    public function rules()
    {
        return [
            'logo' => 'required',
            'favicon' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'address_ar' => 'required',
            'address_en' => 'required',
        ];
    }
}
