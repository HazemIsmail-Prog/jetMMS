<?php

namespace App\Livewire\Dispatching;

use App\Models\Department;
use App\Models\Order;
use App\Models\Shift;
use App\Models\Status;
use App\Models\Title;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class DispatchingIndex extends Component
{
    public Department $department;

    #[Computed()]
    #[On('commentsUpdated')]
    #[On('invoicesUpdated')]
    #[On('holdOrCancelReasonUpdated')]
    #[On('echo:departments.{department.id},RefreshDepartmentScreenEvent')] // Alternative working way
    public function orders()
    {
        return Order::query()
            ->where('department_id', $this->department->id)
            ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
            ->with('address.area:id,name_' . app()->getLocale())

            ->withCount(['status as status_color' => function ($q) {
                $q->select('color');
            }])

            ->withCount(['customer as customer_name' => function ($q) {
                $q->select('name');
            }])

            ->withCount(['phone as phone_number' => function ($q) {
                $q->select('number');
            }])

            ->withCount(['creator as creator_name' => function ($q) {
                $q->select('name_' . app()->getLocale());
            }])

            ->withCount('invoices')

            ->withCount(['comments as unread_comments_count' => function ($q) {
                $q->where('is_read', false);
                $q->where('user_id', '!=', auth()->id());
            }])
            ->orderBy('index')
            ->get();
    }

    #[Computed()]
    public function unAssaignedOrders()
    {
        return $this->orders->where('status_id', Status::CREATED);
    }

    #[Computed()]
    public function onHoldOrders()
    {
        return $this->orders->where('status_id', Status::ON_HOLD);
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->select('id','shift_id' ,'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->where('active', true)
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->where('department_id', $this->department->id)
            ->withCount(['orders_technician as todays_completed_orders_count' => function ($q) {
                $q->where('status_id', Status::COMPLETED);
                $q->whereDate('completed_at', today());
            }])
            ->orderBy('name_' . app()->getLocale())
            ->get()
            //
            ;
    }

    #[Computed(cache: true, key: 'dispatching-shifts')]
    public function shifts()
    {
        return Shift::all();
    }

    public function dragEnd($order_id, $destenation_id, $source_id, $new_index)
    {
        $current_order = Order::find($order_id);

        if ($current_order->department_id == $this->department->id) {
            switch ($destenation_id) {
                case 0: //dragged to unassgined box
                    $current_order->index = $new_index;
                    $current_order->technician_id = null;
                    $current_order->status_id = Status::CREATED;
                    break;

                case 'hold': //dragged to on hold box or hold button clicked
                    $current_order->index = $new_index ?? $this->department->orders()->where('status_id', Status::ON_HOLD)->min('index') - 10;
                    $current_order->technician_id = null;
                    $current_order->status_id = Status::ON_HOLD;
                    break;

                default: // dragged to technician box

                    $destenation_tech = User::find($destenation_id);

                    if ($destenation_tech->active) {
                        if ($new_index) {
                            // this for changing technician on dragging
                            $current_order->index = $new_index;
                        } else {
                            // this for changing technicina from dropdown select
                            $current_order->index =
                                Order::where('technician_id', $destenation_id)
                                ->whereNotIn('status_id', [Status::COMPLETED, Status::CANCELLED])
                                ->max('index') + 10;
                        }
                        $current_order->technician_id = $destenation_id;
                        $current_order->status_id = Status::DESTRIBUTED;
                    }
            }
            $current_order->save();
        }
    }

    public function render()
    {
        return view('livewire.dispatching.dispatching-index')->title($this->department->name);
    }
}
