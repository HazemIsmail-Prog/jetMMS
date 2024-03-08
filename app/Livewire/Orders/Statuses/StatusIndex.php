<?php

namespace App\Livewire\Orders\Statuses;

use App\Models\Order;
use App\Models\OrderStatus;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class StatusIndex extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Order $order;

    #[On('showStatusHistoryModal')]
    public function show(Order $order)
    {
        $this->order = $order;
        $this->modalTitle = __('messages.statuses_for_order_number') . str_pad($this->order->id, 8, '0', STR_PAD_LEFT);
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
        return view('livewire.orders.statuses.status-index');
    }
}
