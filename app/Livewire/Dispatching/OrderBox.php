<?php

namespace App\Livewire\Dispatching;

use App\Events\RefreshDepartmentScreenEvent;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class OrderBox extends Component
{
    public Order $order;
    public $technician_id;
    public $showBox = true;

    public function getListeners()
    {
        return [
            'commentsUpdated' => '$refresh',
            'invoiceCreated' => '$refresh',
            'invoiceDeleted' => '$refresh',
            "echo:departments.{$this->order->department_id},RefreshDepartmentScreenEvent" => '$refresh',
        ];
    }

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->technician_id = $this->order->technician_id;
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->activeTechniciansPerDepartment($this->order->department_id)
            ->get();
    }

    public function updatedTechnicianId($val)
    {
        $this->order->index =
            Order::where('technician_id', $val)
            ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
            ->max('index') + 10;
        $this->order->technician_id = $val;
        $this->order->status_id = Status::DESTRIBUTED;
        $this->order->save();
        $this->showBox = false;
        RefreshDepartmentScreenEvent::dispatch($this->order->department_id);
    }

    public function holdOrder()
    {
        $this->order->technician_id = null;
        $this->order->status_id = Status::ON_HOLD;
        $this->order->save();
        $this->showBox = false;
        RefreshDepartmentScreenEvent::dispatch($this->order->department_id);
    }

    public function cancelOrder()
    {
        $this->order->technician_id = null;
        $this->order->status_id = Status::CANCELLED;
        $this->order->cancelled_at = now();
        $this->order->save();
        $this->showBox = false;
        RefreshDepartmentScreenEvent::dispatch($this->order->department_id);
    }

    public function render()
    {
        return view('livewire.dispatching.order-box');
    }
}
