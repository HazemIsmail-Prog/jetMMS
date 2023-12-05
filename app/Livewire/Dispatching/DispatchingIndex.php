<?php

namespace App\Livewire\Dispatching;

use App\Models\Department;
use App\Models\Order;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class DispatchingIndex extends Component
{
    public Department $department;
    public $change_order_technician = [];

    public $listeners = [
        'commentsUpdated' => '$refresh',
        'invoiceCreated' => '$refresh',
        'invoiceDeleted' => '$refresh',
    ];

    public function mount()
    {
        $this->fillTechnicianIdForEachOrder();
    }

    public function fillTechnicianIdForEachOrder()
    {
        $this->change_order_technician = [];
        foreach ($this->orders as $order) {
            $this->change_order_technician[$order->id]['technician_id'] = $order->technician_id ?? '';
        }
    }

    public function updatedChangeOrderTechnician($val, $key)
    {
        $order_id = explode('.', $key)[0];
        $destenation_id = $val;
        $source_id = Order::find($order_id)->technician_id;
        $new_index = Order::where('technician_id', $destenation_id)
            ->whereNotIn('status_id', [Order::COMPLETED, Order::CANCELLED])
            ->max('index') + 10;

        $this->dragEnd($order_id, $destenation_id, $source_id, $new_index);
    }

    #[Computed]
    #[On('dragEnd')]
    public function orders()
    {
        return Order::query()
            ->where('department_id', $this->department->id)
            ->whereNotIn('status_id', [Order::COMPLETED, Order::CANCELLED])
            ->with('customer:id,name')
            ->with('phone:id,number')
            ->with('address.area')
            ->with('status:id,color as colorrrrr')
            ->with('creator:id,name_en,name_ar')
            ->withCount('comments as all_comments')
            ->withCount('invoices')
            ->withCount(['comments as unread' => function ($q) {
                $q->where('is_read', false);
                $q->where('user_id', '!=', auth()->id());
            }])
            ->orderBy('index')
            ->get();
    }

    #[Computed]
    public function technicians()
    {
        return User::query()
            ->select('id', 'name_ar', 'name_en')
            ->activeTechniciansPerDepartment($this->department->id)
            ->with('shift')
            ->get()
            ->sortBy('name');
    }

    public function dragEnd($order_id, $destenation_id, $source_id, $new_index)
    {
        $current_order = Order::find($order_id);
        $current_order->index = $new_index;

        switch ($destenation_id) {
            case 0: //dragged to unassgined box
                $current_order->technician_id = null;
                $current_order->status_id = 1;
                break;

            case 'hold': //dragged to on hold box
                $current_order->technician_id = null;
                $current_order->status_id = 5;
                break;

            case 'cancel': // cancel button clicked
                $current_order->technician_id = null;
                $current_order->status_id = 6;
                $current_order->cancelled_at = now();
                break;

            default: // dragged to technician box
                $current_order->technician_id = $destenation_id;
                $current_order->status_id = 2;
        }

        $current_order->save();
        $this->dispatch('dragEnd');
        $this->fillTechnicianIdForEachOrder();

        // if any change send event to department dispatch screen

        //if just index changed send to tech if the first one changed

    }

    public function holdOrder($order_id)
    {
        $new_index = $this->orders->where('status_id', 5)->max('index') + 10;
        $this->dragEnd($order_id, 'hold', null, $new_index);
    }
    public function cancelOrder($order_id)
    {
        $this->dragEnd($order_id, 'cancel', null, null);
    }
    public function render()
    {
        return view('livewire.dispatching.dispatching-index');
    }
}
