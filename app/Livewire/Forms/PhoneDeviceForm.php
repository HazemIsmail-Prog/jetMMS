<?php

namespace App\Livewire\Forms;

use App\Models\PhoneDevice;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;

use Livewire\Form;

class PhoneDeviceForm extends Form
{
    public $id;
    public $serial_no;
    public $brand;
    public $model;
    public $sim_no;
    public $status;
    public $notes;
    public $created_by;


    public function rules()
    {
        return [
            'id' => 'nullable',
            'serial_no' => ['required', Rule::unique('phone_devices', 'serial_no')->ignore($this->id)],
            'brand' => 'required',
            'model' => 'required',
            'sim_no' => 'nullable',
            'status' => 'nullable',
            'notes' => 'nullable',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->id) {
            $this->created_by = auth()->id();
        }
        PhoneDevice::updateOrCreate(['id' => $this->id], $this->all());
    }
}
