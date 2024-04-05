<?php

namespace App\Livewire\Orders\Statuses;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class StatusModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Order $order;

    #[On('showStatusesModal')]
    public function show(Order $order)
    {
        $this->order = $order;
        $this->modalTitle = __('messages.statuses_for_order_number') . $this->order->formated_id;
        $this->showModal = true;
    }


    public function render()
    {
        return view('livewire.orders.statuses.status-modal');
    }
}
