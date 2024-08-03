<?php

namespace App\Livewire\Forms;

use App\Models\Marketing;
use Livewire\Form;

class MarketingForm extends Form
{
    public $id;
    public $name;
    public $phone;
    public $address;
    public $type;
    public $notes;
    public $user_id;

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

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->id) {
            $this->user_id = auth()->id();
        }
        Marketing::updateOrCreate(['id' => $this->id], $this->all());
        $this->reset();
    }
}
