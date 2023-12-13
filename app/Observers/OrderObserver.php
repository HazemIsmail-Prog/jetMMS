<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatus;

class OrderObserver
{

    public function creating(Order $order) : void {
        
        $order->index = Order::query()
                    ->where('department_id', $order->department_id)
                    ->where('status_id', 1)
                    ->min('index')
                    - 1;
    }

    public function created(Order $order): void
    {
        // to add order_status record with status id 1 when creating new order
        OrderStatus::create([
            'order_id' => $order->id,
            'status_id' => $order->status_id,
            'technician_id' => $order->technician_id,
            'user_id' => auth()->id() ?? 1,
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // to add order_status record only if status id changed or technecian id changed
        if ($order->isDirty('status_id') || $order->isDirty('technician_id')) {
            OrderStatus::create([
                'order_id' => $order->id,
                'status_id' => $order->status_id,
                'technician_id' => $order->technician_id,
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
