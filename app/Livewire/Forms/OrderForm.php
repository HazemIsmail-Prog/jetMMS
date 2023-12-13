<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class OrderForm extends Form
{
    public $id;

    public $customer_id;
    
    public $phone_id;

    public $address_id;

    public $department_id;

    public $technician_id = '';

    public $estimated_start_date;

    public $order_description;

    public $notes;

    public $tag;

    public function rules() {
        return [
            'id' => 'nullable',
            'customer_id' => 'required',
            'phone_id' => 'required',
            'address_id' => 'required',
            'department_id' => 'required',
            'technician_id' => 'nullable',
            'estimated_start_date' => 'required',
            'order_description' => 'nullable',
            'notes' => 'nullable',
            'tag' => 'nullable',
        ];
    }

}
