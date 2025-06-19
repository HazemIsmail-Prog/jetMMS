<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
class OrderUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Order $order, public int|null $oldDepartmentId = null, public int|null $oldTechnicianId = null)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new Channel('orders'),
            new Channel('departments.' . $this->order->department_id),
        ];

        if ($this->order->technician_id) {
            $channels[] = new Channel('technicians.' . $this->order->technician_id);
        }

        if ($this->oldDepartmentId) {
            $channels[] = new Channel('departments.' . $this->oldDepartmentId);
        }

        if ($this->oldTechnicianId) {
            $channels[] = new Channel('technicians.' . $this->oldTechnicianId);
        }

        return $channels;
    }
}
