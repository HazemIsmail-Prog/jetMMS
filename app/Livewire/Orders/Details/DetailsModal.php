<?php

namespace App\Livewire\Orders\Details;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailsModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Order $order;

    #[On('showDetailsModal')]
    public function show(Order $order)
    {
        $this->order = $order;
        $this->modalTitle = __('messages.details_for_order_number') . ' ' . $this->order->formated_id;
        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.orders.details.details-modal');
    }
}
