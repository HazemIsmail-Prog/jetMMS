<?php

namespace App\Livewire\Orders;

use App\Events\RefreshDepartmentScreenEvent;
use App\Livewire\Forms\OrderForm as FormsOrderForm;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderForm extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Customer $customer;
    public Order $order;
    public $dup_orders_count = 0;
    public FormsOrderForm $form;

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', true)
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->get();
    }

    #[Computed()]
    #[On('departmentSelected')]
    public function technicians()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->where('department_id', $this->form->department_id)
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }

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
            $this->form->customer_id = $this->customer->id;
            $this->form->phone_id = $this->customer->phones->count() == 1 ? $this->customer->phones()->first()->id : null;
            $this->form->address_id = $this->customer->addresses->count() == 1 ? $this->customer->addresses()->first()->id : null;
            $this->form->estimated_start_date = today()->format('Y-m-d');
        } else {
            //edit
            $this->modalTitle = __('messages.edit_order') . str_pad($this->order->id, 8, '0', STR_PAD_LEFT);
            $this->form->fill($this->order);
        }
    }

    public function updated($key)
    {
        if ($key == 'form.department_id') {
            $this->dispatch('departmentSelected');
        }
        if (in_array($key, ['form.department_id', 'form.estimated_start_date', 'form.address_id'])) {
            $this->dup_orders_count = Order::query()
                ->where([
                    'customer_id' => $this->form->customer_id,
                    'department_id' => $this->form->department_id,
                ])
                ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
                ->when($this->order, function ($q) {
                    $q->where('id', '!=', $this->order->id);
                })
                ->count();
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->form->reset('customer_id');
        $this->showModal = false;
        $this->dispatch('ordersUpdated');
        RefreshDepartmentScreenEvent::dispatch($this->form->department_id);
    }

    public function render()
    {
        return view('livewire.orders.order-form');
    }
}
