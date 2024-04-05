<?php

namespace App\Livewire\Orders\Statuses;

use App\Models\Order;
use App\Models\OrderStatus;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class StatusIndex extends Component
{
    public Order $order;

    #[Computed]
    #[On('echo:statuses.{order.id},RefreshOrderStatusesScreenEvent')]
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
