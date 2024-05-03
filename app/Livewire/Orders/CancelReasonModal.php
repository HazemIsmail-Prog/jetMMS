<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Models\Status;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CancelReasonModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public $modalDescription = '';
    #[Rule('required|string|min:5')]
    public $reason = '';
    public Order $order;

    #[On('showCancelReasonModal')]
    public function show(Order $order)
    {
        $this->order = $order;
        $this->modalTitle = __('messages.cancel_order') . ' ' . $this->order->formated_id;
        $this->modalDescription = __('messages.please_provide_reason_for_cancel');
        $this->showModal = true;

        $this->js("
        setTimeout(function() { 
            document.getElementById('reason').focus();
         }, 50);
        ");
    }

    public function save()
    {
        $this->validate();
        $this->order->technician_id = null;
        $this->order->status_id = Status::CANCELLED;
        $this->order->cancelled_at = now();
        $this->order->reason = $this->reason;
        $this->order->save();
        $this->order->update(['reason' => null]);

        $this->dispatch('holdOrCancelReasonUpdated');
        $this->reset('reason', 'order');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.orders.cancel-reason-modal');
    }
}
