<?php

namespace App\Livewire\Dispatching;

use App\Models\Department;
use App\Models\Order;
use App\Models\Shift;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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

    #[Computed()]
    public function orders()
    {
        return Order::query()
            ->where('department_id', $this->department->id)
            ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
            ->with('status:id,color')
            ->with('customer:id,name')
            ->with('phone:id,number')
            ->with('address.area:id,name_' . app()->getLocale())
            ->with('creator:id,name_' . app()->getLocale())
            ->withCount('invoices')
            ->withCount('comments')
            ->withCount(['comments as unread_comments_count' => function ($q) {
                $q->where('is_read', false);
                $q->where('user_id', '!=', auth()->id());
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
            ->withCount(['orders_technician as todays_completed_orders_count' => function ($q) {
                $q->where('status_id', Status::COMPLETED);
                $q->whereDate('completed_at', today());
            }])
            ->withCount(['orders_technician as current_orders_count' => function ($q) {
                $q->whereIn('status_id', [Status::DESTRIBUTED, Status::RECEIVED, Status::ARRIVED]);
            }])
            ->get();
    }

    #[Computed()]
    public function shifts()
    {
        return Shift::all();
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

    public function changeTechnician($technician_id, $order_id)
    {
        $order = Order::find($order_id);
        $order->index =
            Order::where('technician_id', $technician_id)
            ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
            ->max('index') + 10;
        $order->technician_id = $technician_id;
        $order->status_id = Status::DESTRIBUTED;
        $order->save();
    }

    public function render()
    {
        // dd($this->shifts());
        return view('livewire.dispatching.dispatching-index');
    }
}
