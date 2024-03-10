<?php

namespace App\Livewire\Dispatching;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Order;
use App\Models\Phone;
use App\Models\Status;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DispatchingIndex extends Component
{
    public Department $department;

    public function getListeners()
    {
        return [
            'commentsUpdated' => '$refresh',
            'invoicesUpdated' => '$refresh',
            "echo:departments.{$this->department->id},RefreshDepartmentScreenEvent" => '$refresh',
        ];
    }

    function test($event)
    {
        $this->dispatch("refreshBoxForOrderNo.{$event['orderID']}");
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
            ->withCount('invoices')
            ->withCount('comments')
            ->withCount(['comments as unread_comments_count' => function ($q) {
                $q->where('is_read', false);
                $q->where('user_id','!=', auth()->id());
            }])
            ->orderBy('index')
            ->get();
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->activeTechniciansPerDepartment($this->department->id)
            ->with('shift')
            ->get();
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
    }

    public function render()
    {
        return view('livewire.dispatching.dispatching-index');
    }
}
