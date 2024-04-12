<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Models\Status;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class HoldOrCancelReasonModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public $modalDescription = '';
    #[Rule('required|string|min:5')]
    public $reason = '';
    public $action = '';
    public $index = 0;
    public Order $order;

    #[On('showHoldOrCancelReasonModal')]
    public function show(Order $order, $action, $index)
    {
        $this->order = $order;
        $this->action = $action;
        $this->index = $index;
        $this->modalTitle = __('messages.' . $this->action . '_order') . ' ' . $this->order->formated_id;
        $this->modalDescription = __('messages.please_provide_reason_for_' . $this->action);
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
        if ($this->action === 'hold') {
            $this->order->index = $this->index;
            $this->order->technician_id = null;
            $this->order->status_id = Status::ON_HOLD;
            $this->order->reason = $this->reason;
            $this->order->save();
        }
        if ($this->action === 'cancel') {
            $this->order->technician_id = null;
            $this->order->status_id = Status::CANCELLED;
            $this->order->cancelled_at = now();
            $this->order->reason = $this->reason;
            $this->order->save();
        }
        $this->order->update(['reason' => null]);

        $this->dispatch('holdOrCancelReasonUpdated');
        $this->reset('reason', 'order', 'action');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.orders.hold-or-cancel-reason-modal');
    }
}
