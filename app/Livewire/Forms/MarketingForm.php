<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class MarketingForm extends Form
{
    public $id;
    public $name;
    public $phone;
    public $address;
    public $type;
    public $notes;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'nullable',
            'type' => 'required',
            'notes' => 'nullable',
        ];
    }
}
