<?php

namespace App\Livewire\Orders;

use App\Events\RefreshDepartmentPageEvent;
use App\Events\RefreshDepartmentScreenEvent;
use App\Livewire\Forms\OrderForm as FormsOrderForm;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Order;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderForm extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Customer $customer;
    public Order $order;
    public $departments = [];
    public $technicians = [];
    public $dup_orders_count = 0;
    public FormsOrderForm $form;

    #[On('showOrderFormModal')]
    public function show(Customer $customer, Order $order)
    {

        $this->reset('customer', 'order', 'dup_orders_count');
        $this->form->reset();
        $this->showModal = true;
        $this->order = $order;
        $this->customer = $customer;
        if (!$this->order->id) {
            //create
            $this->modalTitle = __('messages.add_order');
            $this->departments = Department::query()
                ->where('is_service', true)
                ->select('id', 'name_en', 'name_ar')
                ->get();
            $this->form->customer_id = $this->customer->id;
            $this->form->phone_id = $this->customer->phones->count() == 1 ? $this->customer->phones()->first()->id : null;
            $this->form->address_id = $this->customer->addresses->count() == 1 ? $this->customer->addresses()->first()->id : null;
            $this->form->estimated_start_date = today()->format('Y-m-d');
        } else {
            //edit
            $this->modalTitle = __('messages.add_order') . $this->order->id;
            $this->departments = $this->order->technician_id ? Department::whereId($this->order->department_id)->get() : Department::where('is_service', 1)->get();
            $this->updated('form.department_id', $this->order->department_id);
            $this->form->fill($this->order);
        }
    }


    public function updated($key, $val)
    {
        if ($key == 'form.department_id') {
            $this->form->technician_id = '';
            if ($val) {
                $this->technicians = Department::find($val)->technicians->where('active', true);
            }
        }

        if (in_array($key, ['form.department_id', 'form.estimated_start_date', 'form.address_id'])) {
            $this->dup_orders_count = Order::query()
                ->where([
                    'address_id' => $this->form->address_id,
                    'department_id' => $this->form->department_id,
                    // 'estimated_start_date' => $this->estimated_start_date,
                ])
                ->whereNotIn('status_id', [4, 6])
                ->when($this->order, function ($q) {
                    $q->where('id', '!=', $this->order->id);
                })
                ->count();
        }
    }

    public function save()
    {
        $this->validate();

        if (!$this->order->id) {
            //create
            $data = [
                'customer_id' => $this->form->customer_id,
                'phone_id' => $this->form->phone_id,
                'address_id' => $this->form->address_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'department_id' => $this->form->department_id,
                'estimated_start_date' => $this->form->estimated_start_date,
                'order_description' => $this->form->order_description,
                'notes' => $this->form->notes,
                'tag' => $this->form->tag,
                'technician_id' => null,
                'status_id' => 1,
                // index setted via observer
            ];
            $this->order = Order::create($data);

            if ($this->form->technician_id) {
                $this->order->update([
                    'technician_id' => $this->form->technician_id,
                    'status_id' => 2,
                    'index' => Order::query()
                    ->where('technician_id', $this->form->technician_id)
                    ->where('status_id', 2)
                    ->max('index')
                    + 1,
                ]);
            }

            // $this->form->department_id = null; // this line to avoid order duplication with the user click on save many times
        } else {
            //edit
            $data = [
                'customer_id' => $this->form->customer_id,
                'phone_id' => $this->form->phone_id,
                'address_id' => $this->form->address_id,
                'updated_by' => auth()->id(),
                'department_id' => $this->form->department_id,
                'estimated_start_date' => $this->form->estimated_start_date,
                'order_description' => $this->form->order_description,
                'notes' => $this->form->notes,
                'tag' => $this->form->tag,
            ];
            $this->order->update($data);
        }

        // $this->form->reset();
        $this->showModal = false;
        RefreshDepartmentScreenEvent::dispatch($this->form->department_id);

    }

    public function render()
    {
        return view('livewire.orders.order-form');
    }
}