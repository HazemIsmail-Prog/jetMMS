<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Models\OrderStatus;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class StatusHistoryModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Order $order;

    #[On('showStatusHistoryModal')]
    public function show($order_id)
    {
        $this->reset();
        $this->order = Order::find($order_id);
        $this->modalTitle = __('messages.statuses_for_order_number') . $order_id;
        $this->showModal = true;
    }

    #[Computed]
    public function statuses()
    {
        return OrderStatus::query()
            ->where('order_id', $this->order->id)
            ->with('status')
            ->with('technician')
            ->with('creator')
            ->get();
    }

    public function render()
    {
        return view('livewire.orders.status-history-modal');
    }
}
