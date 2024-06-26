<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class TechnicianPage extends Component
{

    public function getListeners()
    {
        $authId = auth()->id();
        return [
            "echo:technicians.{$authId},RefreshTechnicianScreenEvent" => '$refresh',
        ];
    }

    #[Computed()]
    #[On('order_updated')]
    #[On('invoicesUpdated')]
    public function order()
    {
        return auth()->user()->current_order_for_technician;
    }

    public function accept_order()
    {
        $order = Order::find($this->order->id);
        if ($order->technician_id == auth()->id()) {
            $this->order->update(['status_id' => 3]);
        }
    }

    public function arrived_order()
    {
        $order = Order::find($this->order->id);
        if ($order->technician_id == auth()->id()) {
            $this->order->update(['status_id' => 7]);
        }
    }

    public function complete_order()
    {

        if ($this->order->invoices->count() == 0 && Setting::find(1)->invoice_required) {
            return;
        }

        $order = Order::find($this->order->id);
        if ($order->technician_id == auth()->id()) {
            $this->order->update([
                'status_id' => 4,
                'completed_at' => now(),
                'index' => null,
            ]);
        }
        $this->dispatch('order_updated');
    }

    #[Layout('layouts.technician')]
    public function render()
    {
        return view('livewire.technician-page');
    }
}
