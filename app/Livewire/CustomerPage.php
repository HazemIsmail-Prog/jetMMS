<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CustomerPage extends Component
{
    public Order $order;

    protected $listeners = ['ratingsUpdated' => '$refresh'];


    public function mount($encryptedOrderId)
    {

        $this->order = Order::find(decrypt($encryptedOrderId));

        // if ($this->order->rating?->count() > 0) {
        //     dd('Already Rated');
        // } else {
        //     dd($this->order->id);
        // }
    }

    #[Layout('layouts.customer')]
    public function render()
    {
        return view('livewire.customer-page');
    }
}
