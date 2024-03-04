<?php

namespace App\Observers;

use App\Events\RefreshDepartmentScreenEvent;
use App\Events\RefreshTechnicianScreenEvent;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Status;
use App\Models\User;

class OrderObserver
{

    public function creating(Order $order): void
    {

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

        RefreshDepartmentScreenEvent::dispatch($order->department_id);


        if ($order->isDirty('technician_id') || $order->isDirty('index')) {
            $oldTechnician = User::find($order->getOriginal('technician_id'));
            $newTechnician = User::find($order->technician_id);

            if ($newTechnician) {
                if ($newTechnician->current_order_for_technician?->id == $order->id) {
                    RefreshTechnicianScreenEvent::dispatch($newTechnician->id);
                }
            }

            if ($oldTechnician) {

                if(!$oldTechnician->current_order_for_technician){
                    RefreshTechnicianScreenEvent::dispatch($oldTechnician->id);
                    return;
                }

                if ($oldTechnician->current_order_for_technician->id != $order->id && $order->index< $oldTechnician->current_order_for_technician->index ) {
                    RefreshTechnicianScreenEvent::dispatch($oldTechnician->id);
                }

            }
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
