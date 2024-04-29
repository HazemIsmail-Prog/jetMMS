<?php

namespace App\Livewire\Forms;

use App\Models\Order;
use App\Models\Status;
use Livewire\Form;

class OrderForm extends Form
{
    public $id;
    public $status_id;
    public $customer_id;
    public $phone_id;
    public $address_id;
    public $department_id;
    public $technician_id;
    public $estimated_start_date;
    public $order_description;
    public $notes;
    public $tag;
    public $created_by;
    public $updated_by;

    public function rules()
    {
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

    public function updateOrCreate()
    {
        $this->validate();

        if (!$this->id) {
            // Create State
            $this->created_by = auth()->id();
            $this->updated_by = auth()->id();
            $this->status_id = 1;
            $order = Order::updateOrCreate(['id' => $this->id], $this->except('technician_id'));
            if ($this->technician_id) {
                $order->update([
                    'technician_id' => $this->technician_id,
                    'status_id' => Status::DESTRIBUTED,
                    'index' => Order::query()
                        ->where('technician_id', $this->technician_id)
                        ->whereNotIn('status_id', [Status::COMPLETED,Status::CANCELLED])
                        ->max('index')
                        + 10,
                ]);
            }
        } else {
            // Edit State
            $this->updated_by = auth()->id();
            $order = Order::updateOrCreate(['id' => $this->id], $this->except('technician_id','creted_by','status_id'));
        }


    }
}
