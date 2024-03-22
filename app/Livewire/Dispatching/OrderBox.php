<?php

namespace App\Livewire\Dispatching;

use App\Models\Order;
use App\Models\Status;
use Livewire\Component;

class OrderBox extends Component
{
    public Order $order;
    public bool $animate = false;
    public $technician_id;
    public $technicians;
    public $showBox = true;

    public function getListeners()
    {
        return [
            "refreshBoxForOrderNo.{$this->order->id}" => '$refresh',
        ];
    }

    public function mount()
    {
        $this->technician_id = $this->order->technician_id;
        $this->animate = $this->order->unread_comments_count > 0;
    }

    public function updatedTechnicianId($val)
    {
        $this->order->index =
            Order::where('technician_id', $val)
            ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
            ->max('index') + 10;
        $this->order->technician_id = $val;
        $this->order->status_id = Status::DESTRIBUTED;
        $this->order->save();
        $this->showBox = false;
    }

    public function holdOrder()
    {
        $this->order->technician_id = null;
        $this->order->status_id = Status::ON_HOLD;
        $this->order->save();
        $this->showBox = false;
    }

    public function cancelOrder()
    {
        $this->order->technician_id = null;
        $this->order->status_id = Status::CANCELLED;
        $this->order->cancelled_at = now();
        $this->order->save();
        $this->showBox = false;
    }

    public function render()
    {
        return view('livewire.dispatching.order-box');
    }
}
