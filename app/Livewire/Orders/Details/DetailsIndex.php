<?php

namespace App\Livewire\Orders\Details;

use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailsIndex extends Component
{
    public Order $order;

    #[Computed()]
    #[On('echo:statuses.{order.id},RefreshOrderStatusesScreenEvent')]
    public function render()
    {
        return view('livewire.orders.details.details-index');
    }
}
