<?php

namespace App\Livewire\Forms;

use App\Models\PhoneDeviceAction;
use Livewire\Form;

class PhoneDeviceActionForm extends Form
{
    public $id;
    public $phone_device_id;
    public $created_by;
    public int | null $from_id;
    public int | null $to_id;
    public $notes;
    public $date;
    public $time;

    public function rules()
    {
        return [
            'from_id' => 'nullable',
            'to_id' => 'nullable',
            'notes' => 'nullable',
            'date' => 'required',
            'time' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->id) {
            $this->created_by = auth()->id();
        }
        PhoneDeviceAction::updateOrCreate(['id' => $this->id], $this->all());
        $this->reset();

    }
}
