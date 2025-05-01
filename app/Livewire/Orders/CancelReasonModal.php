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
    #[Rule('required|string')]
    public $reason = '';
    #[Rule('required_if:reason,اسباب أخرى|string|min:5')]
    public $otherReason = '';
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
        $reason = $this->reason == 'اسباب أخرى' ? $this->reason . ' - ' . $this->otherReason : $this->reason;
        $this->order->technician_id = null;
        $this->order->status_id = Status::CANCELLED;
        $this->order->cancelled_at = now();
        $this->order->reason = $reason;
        $this->order->save();
        // $this->order->update(['reason' => null]);

        $this->dispatch('holdOrCancelReasonUpdated');
        $this->reset('reason', 'otherReason', 'order');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.orders.cancel-reason-modal');
    }
}
