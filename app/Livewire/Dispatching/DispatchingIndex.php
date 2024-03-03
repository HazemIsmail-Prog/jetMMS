<?php

namespace App\Livewire\Dispatching;

use App\Events\RefreshDepartmentScreenEvent;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Order;
use App\Models\Phone;
use App\Models\Status;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class DispatchingIndex extends Component
{
    public Department $department;

    public function getListeners()
    {
        return [
            // 'commentsUpdated' => '$refresh',
            // 'invoiceCreated' => '$refresh',
            // 'invoiceDeleted' => '$refresh',
            "echo:departments.{$this->department->id},RefreshDepartmentScreenEvent" => '$refresh',
        ];
    }

    #[Computed()]
    public function orders()
    {
        return Order::query()
            ->where('department_id', $this->department->id)
            ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
            ->addSelect(['customer_name' => Customer::select('name')->whereColumn('id', 'orders.customer_id')])
            ->addSelect(['phone_number' => Phone::select('number')->whereColumn('id', 'orders.phone_id')])
            ->addSelect(['status_color' => Status::select('color')->whereColumn('id', 'orders.status_id')])
            ->addSelect(['order_creator' => User::select('name_' . app()->getLocale())->whereColumn('id', 'orders.created_by')])
            ->with('address.area')
            ->withCount('invoices as custom_invoices_count')
            ->with('comments')
            ->orderBy('index')
            ->get();
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->select('id', 'name_ar', 'name_en')
            ->activeTechniciansPerDepartment($this->department->id)
            ->with('shift')
            ->get()
            ->sortBy('name');
    }

    public function dragEnd($order_id, $destenation_id, $source_id, $new_index)
    {
        $current_order = Order::find($order_id);
        $current_order->index = $new_index;

        switch ($destenation_id) {
            case 0: //dragged to unassgined box
                $current_order->technician_id = null;
                $current_order->status_id = Status::CREATED;
                break;

            case 'hold': //dragged to on hold box
                $current_order->technician_id = null;
                $current_order->status_id = Status::ON_HOLD;
                break;

            case 'cancel': // cancel button clicked
                $current_order->technician_id = null;
                $current_order->status_id = Status::CANCELLED;
                $current_order->cancelled_at = now();
                break;

            default: // dragged to technician box
                $current_order->technician_id = $destenation_id;
                $current_order->status_id = Status::DESTRIBUTED;
        }

        $current_order->save();

        // if any change send event to department dispatch screen
        RefreshDepartmentScreenEvent::dispatch($this->department->id);


        // TODO: if just index changed send to tech if the first one changed

    }

    public function render()
    {
        return view('livewire.dispatching.dispatching-index');
    }
}
