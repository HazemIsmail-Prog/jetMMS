<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Order;
use App\Models\User;
use App\Models\Status;
use App\Http\Resources\OrderResource;
use App\Models\Title;
use Illuminate\Http\Request;

class DispatchingPageController extends Controller
{
    public function index(Department $department)
    {
        $titles = Title::whereIn('id', Title::TECHNICIANS_GROUP)->orderBy('id', 'desc')->get();

        $technicians = User::query()
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->where('department_id', $department->id)
            ->where('active', 1)
            ->orderBy('name_' . app()->getLocale())
            ->get();

        $orders = Order::query()
            ->with('customer')
            ->with('phone')
            ->with('department')
            ->with('address')
            ->with('creator')
            ->withCount('invoices')
            ->where('department_id', $department->id)
            ->whereIn('status_id', [Status::CREATED, Status::DESTRIBUTED, Status::ON_HOLD, Status::RECEIVED, Status::ARRIVED])
            ->orWhere(function($query) use ($department) {
                $query
                    ->where('status_id', Status::COMPLETED)
                    ->where('department_id', $department->id)
                    ->whereDate('completed_at', today());
            })
            ->orWhere(function($query) use ($department) {
                $query
                    ->where('status_id', Status::CANCELLED)
                    ->where('department_id', $department->id)
                    ->whereDate('cancelled_at', today());
            })
            ->get();

        return view('pages.dispatching.index', [
            'department' => $department,
            'technicians' => $technicians,
            'orders' => OrderResource::collection($orders),
            'titles' => $titles,
        ]);
    }

    public function getTodaysCompletedOrdersForTechnician(User $user)
    {
        $orders = Order::query()
            ->with('department')
            ->with('customer')
            ->with('phone')
            ->with('address')
            ->with('creator')
            ->with('technician')
            ->withCount('invoices')
            ->with('invoices.invoice_details')
            ->with('invoices.invoice_part_details')
            ->with('invoices.payments.user')
            ->where('technician_id', $user->id)
            ->where('status_id', Status::COMPLETED)
            ->whereDate('completed_at', now())
            ->orderBy('completed_at', 'desc')
            ->get();

        return OrderResource::collection($orders);    
    }

}
