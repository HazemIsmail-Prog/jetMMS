<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Status;
use App\Models\Title;
use App\Models\User;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Comment;
use App\Models\Voucher;
use App\Models\Service;
use App\Models\OrderStatus;
use App\Models\VoucherDetail;
use App\Models\InvoiceDetails;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\OrderStatusResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ServiceResource;
use App\Events\OrderCreatedEvent;
use App\Events\OrderUpdatedEvent;
use App\Events\OrderCompletedEvent;
use App\Events\OrderInvoiceCreatedEvent;
use App\Events\OrderInvoiceUpdatedEvent;
use App\Events\OrderInvoiceDeletedEvent;
use App\Events\OrderCommentCreatedEvent;
use App\Events\InvoicePaymentsUpdatedEvent;
use App\Services\ActionsLog;
use App\Services\InvoiceService;
use App\Services\CreateInvoiceVoucher;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('viewAny', Order::class), 403);

        if ($request->wantsJson()) {
            $orders = Order::query()
                ->with('phone')
                ->with('address')
                ->with('customer')
                ->with('department')
                ->with('invoices')
                ->with('invoices.invoice_details')
                ->with('invoices.invoice_part_details')
                ->with('invoices.payments')
                ->when($request->customer_name, function($query) use ($request) {
                    $query->whereRelation('customer', 'name', 'like', '%' . $request->customer_name . '%');
                })
                ->when($request->customer_phone, function($query) use ($request) {
                    $query->whereRelation('phone', 'number', 'like', '%' . $request->customer_phone . '%');
                })
                ->when($request->area_ids, function($query) use ($request) {
                    $query->whereHas('address', function($query) use ($request) {
                        $query->whereIn('area_id', $request->area_ids);
                    });
                })
                ->when($request->block, function($query) use ($request) {
                    $query->whereRelation('address', 'block', 'like', '%' . $request->block . '%');
                })
                ->when($request->street, function($query) use ($request) {      
                    $query->whereRelation('address', 'street', 'like', '%' . $request->street . '%');
                })
                ->when($request->jadda, function($query) use ($request) {
                    $query->whereRelation('address', 'jadda', 'like', '%' . $request->jadda . '%');
                })
                ->when($request->building, function($query) use ($request) {
                    $query->whereRelation('address', 'building', 'like', '%' . $request->building . '%');
                })
                ->when($request->order_number, function($query) use ($request) {
                    $query->where('id', '=', $request->order_number);    
                })
                ->when($request->creator_ids, function($query) use ($request) {
                    $query->whereIn('created_by', $request->creator_ids);
                })
                ->when($request->status_ids, function($query) use ($request) {
                    $query->whereIn('status_id', $request->status_ids);
                })
                ->when($request->technician_ids, function($query) use ($request) {
                    $query->whereIn('technician_id', $request->technician_ids);
                })
                ->when($request->department_ids, function($query) use ($request) {
                    $query->whereIn('department_id', $request->department_ids);
                })
                ->when($request->tags, function($query) use ($request) {    
                    $query->where('tag', 'like', '%' . $request->tags . '%');
                })
                ->when($request->start_created_at, function($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_created_at);
                })
                ->when($request->end_created_at, function($query) use ($request) {  
                    $query->whereDate('created_at', '<=', $request->end_created_at);
                })
                ->when($request->start_completed_at, function($query) use ($request) {
                    $query->whereDate('completed_at', '>=', $request->start_completed_at);
                })
                ->when($request->end_completed_at, function($query) use ($request) {    
                    $query->whereDate('completed_at', '<=', $request->end_completed_at);
                })
                ->when($request->start_cancelled_at, function($query) use ($request) {
                    $query->whereDate('cancelled_at', '>=', $request->start_cancelled_at);
                })
                ->when($request->end_cancelled_at, function($query) use ($request) {        
                    $query->whereDate('cancelled_at', '<=', $request->end_cancelled_at);
                })
                ->orderBy('id','desc')
                ->paginate(10);
            return OrderResource::collection($orders);
        }

        return view('pages.orders.index', [
            'tags' => Order::select('tag')->distinct()->whereNotNull('tag')->where('tag', '!=', '')->get()->pluck('tag')->unique()->values(),
            'departments' => DepartmentResource::collection(Department::where('is_service', true)->orderByDesc('position')->get()),
            'technicians' => UserResource::collection(User::whereHas('orders_technician')->get()),
            'creators' => UserResource::collection(User::whereHas('orders_creator')->get()),
        ]);
    }

    public function store(Request $request, Customer $customer)
    {
        if(!auth()->user()->can('create', Order::class)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_create_order')], 403);
        }
        // validate the request
        $validatedData = $request->validate([
            'phone_id' => 'required|exists:phones,id',
            'address_id' => 'required|exists:addresses,id',
            'department_id' => 'required|exists:departments,id',
            'estimated_start_date' => 'required|date',
            'notes' => 'nullable|string',
            'order_description' => 'nullable|string',
            'tag' => 'nullable|string',
        ]);

        $validatedData['status_id'] = 1;
        $validatedData['created_by'] = auth()->id();
        $validatedData['updated_by'] = auth()->id();

        if($request->technician_id) {
            // check if user has permission to dispatch orders
            if(!auth()->user()->hasPermission('dispatching_menu')) {
                return response()->json(['error' => __('messages.you_dont_have_permission_to_dispatch_orders')], 403);
            }

            // check if technician is valid
            $technician = User::find($request->technician_id);
            // check if technician is active
            if($technician->active == 0) {
                return response()->json(['error' => __('messages.technician_is_not_active')], 400);
            }
    
            // check if technician is in the same department as the order
            if($technician->department_id != $validatedData['department_id']) {
                return response()->json(['error' => __('messages.technician_is_not_in_the_same_department_as_the_order')], 400);
            }

            // get the index of the order
            $index = Order::query()
                ->where('department_id', $validatedData['department_id'])
                ->where('technician_id', $request->technician_id)
                ->where('status_id','!=', Status::COMPLETED)
                ->max('index') + 10;

            $validatedData['index'] = $index;
        }else{
            // get the index of the order
            $validatedData['index'] = Order::query()
            ->where('department_id', $validatedData['department_id'])
            ->where('status_id', Status::CREATED)
            ->min('index')
            - 10;
        }


        DB::beginTransaction();

            try {
                // create the order
                $order = $customer->orders()->create($validatedData);

                // create the order status
                $this->createOrderStatus($order);

                // if technician is provided, update the order status to destributed and set the technician id
                if($request->technician_id) {
                    // update the order status to destributed and set the technician id
                    $order->update([
                        'status_id' => Status::DESTRIBUTED,
                        'technician_id' => $request->technician_id,
                    ]);
                    // create the order status
                    $this->createOrderStatus($order);
                }

                // commit the transaction
                DB::commit();

                // log the action
                ActionsLog::logAction('Order', 'Created', $order->id, 'Order created successfully', $order->fresh()->toArray());

                // broadcast created event to current channels
                broadcast(new OrderCreatedEvent($order))->toOthers();

                // if technician is provided, broadcast updated event to current channels
                if($request->technician_id) {
                    broadcast(new OrderUpdatedEvent(order:$order))->toOthers();
                }

                // return the order resource
                return new OrderResource($order);
                
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => __('messages.error_creating_order')], 500);
            }

    }

    public function update(Request $request, Order $order)
    {
        // check if auth user has permission to update the order or is the creator of the order
        if(!auth()->user()->hasPermission('orders_edit') && $order->created_by != auth()->id()) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_update_order')], 403);
        }

        if($order->status_id !== Status::CREATED && $order->department_id !== $request->department_id) {
            // that means the user is trying to change the department while the order is not in created status
            // so we need to return an error
            return response()->json(['error' => __('messages.you_can_only_update_department_while_the_order_is_in_created_status')], 400);
        }



        $validatedData = $request->validate([
            'phone_id' => 'required|exists:phones,id',
            'address_id' => 'required|exists:addresses,id',
            'department_id' => 'required|exists:departments,id',
            'estimated_start_date' => 'required|date',
            'notes' => 'nullable|string',
            'order_description' => 'nullable|string',
            'tag' => 'nullable|string',
        ]);

        $oldOrder = $order->fresh()->toArray();

        if($oldOrder['department_id'] !== $validatedData['department_id']) {
            // this means the department has changed
            $oldDepartmentId = $oldOrder['department_id'];
            $orderNewIndex = Order::query()
                ->where('department_id', $validatedData['department_id'])
                ->where('status_id', Status::CREATED)
                ->min('index') - 10;
            $validatedData['index'] = $orderNewIndex;
        } else {
            $oldDepartmentId = null;
        }

        $order->update($validatedData);

        ActionsLog::logAction('Order', 'Updated', $order->id, 'Order updated successfully', $order->fresh()->toArray(), $oldOrder);
        broadcast(new OrderUpdatedEvent(order:$order, oldDepartmentId: $oldDepartmentId))->toOthers(); // broadcast to current channels
        return new OrderResource($order->load('phone', 'address', 'customer', 'department', 'invoices', 'invoices.invoice_details', 'invoices.invoice_part_details', 'invoices.payments'));
    }

    public function show(Order $order)
    {
        $order
            ->load('status', 'department', 'technician', 'customer', 'phone', 'address','creator', 'invoices', 'invoices.invoice_details', 'invoices.invoice_part_details', 'invoices.payments')
            ->loadCount('invoices');
        return new OrderResource($order);
    }

    public function changeTechnician(Request $request, Order $order)
    {
        if(!auth()->user()->hasPermission('dispatching_menu')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_dispatch_orders')], 403);
        }

        if ($order->status_id == Status::RECEIVED || $order->status_id == Status::ARRIVED || $order->status_id == Status::COMPLETED || $order->status_id == Status::CANCELLED) {
            return response()->json(['error' => __('messages.order_is_already_received_or_arrived_or_completed_or_cancelled')], 400);
        }

        $technician = User::find($request->technician_id);
        // check if technician is active
        if($technician->active == 0) {
            return response()->json(['error' => __('messages.technician_is_not_active')], 400);
        }

        // check if technician is in the same department as the order
        if($technician->department_id != $order->department_id) {
            return response()->json(['error' => __('messages.technician_is_not_in_the_same_department_as_the_order')], 400);
        }

        // get the old technician id before updating the order to broadcast to the old technician after updating the order
        $oldTechnicianId = $order->technician_id;
        $oldOrder = $order->fresh()->toArray();

        DB::beginTransaction();

        try {
            $order->update([
                'status_id' => $request->status_id,
                'technician_id' => $request->technician_id,
                'index' => $request->index,
            ]);
            $this->createOrderStatus($order);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('messages.error_updating_order')], 500);
        }

        ActionsLog::logAction('Order', 'Technician Updated', $order->id, 'Technician updated successfully', $order->fresh()->toArray(), $oldOrder);

        // broadcast to current channels
        broadcast(new OrderUpdatedEvent(order:$order, oldTechnicianId: $oldTechnicianId))->toOthers();

        return new OrderResource($order->loadCount('invoices'));
    }

    public function setPending(Request $request, Order $order)
    {
        if(!auth()->user()->hasPermission('dispatching_menu')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_dispatch_orders')], 403);
        }

        if ($order->status_id == Status::COMPLETED || $order->status_id == Status::CANCELLED || $order->status_id == Status::RECEIVED || $order->status_id == Status::ARRIVED) {
            return response()->json(['error' => __('messages.order_is_already_completed_or_cancelled_or_received_or_arrived')], 400);
        }

        if($order->invoices->count() > 0) {
            return response()->json(['error' => __('messages.order_has_invoices')], 400);
        }

        // get the old technician id before updating the order to broadcast to the old technician after updating the order
        $oldTechnicianId = $order->technician_id;
        $oldOrder = $order->fresh()->toArray();

        DB::beginTransaction();

        try {
            $order->update([
            'status_id' => Status::CREATED,
            'technician_id' => null,
            'index' => $request->index,
            ]);
            $this->createOrderStatus($order);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('messages.error_updating_order')], 500);
        }

        ActionsLog::logAction('Order', 'Set On Pending', $order->id, 'Order set on pending successfully', $order->fresh()->toArray(), $oldOrder);

        // broadcast to current channels
        broadcast(new OrderUpdatedEvent(order:$order, oldTechnicianId: $oldTechnicianId))->toOthers();

        return new OrderResource($order->loadCount('invoices'));
    }

    public function changeIndex(Request $request, Order $order)
    {
        if(!auth()->user()->hasPermission('dispatching_menu')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_dispatch_orders')], 403);
        }

        if ($order->status_id == Status::RECEIVED || $order->status_id == Status::ARRIVED || $order->status_id == Status::COMPLETED || $order->status_id == Status::CANCELLED) {
            return response()->json(['error' => __('messages.order_is_already_received_or_arrived_or_completed_or_cancelled')], 400);
        }

        $oldOrder = $order->fresh()->toArray();
        $order->update(['index' => $request->index]);

        ActionsLog::logAction('Order', 'Index Updated', $order->id, 'Order index updated successfully', $order->fresh()->toArray(), $oldOrder);

        broadcast(new OrderUpdatedEvent($order))->toOthers();

        return new OrderResource($order->loadCount('invoices'));
    }

    public function setHold(Request $request, Order $order)
    {
        if(!auth()->user()->hasPermission('orders_hold')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_hold_orders')], 403);
        }

        if ($order->status_id == Status::COMPLETED || $order->status_id == Status::CANCELLED) {
            return response()->json(['error' => __('messages.order_is_already_completed_or_cancelled')], 400);
        }

        if($order->invoices->count() > 0) {
            return response()->json(['error' => __('messages.order_has_invoices')], 400);
        }

        // get the old technician id before updating the order to broadcast to the old technician after updating the order
        $oldTechnicianId = $order->technician_id;
        $oldOrder = $order->fresh()->toArray();

        DB::beginTransaction();

        // update the order status to on hold
        try {
            $order->update([
            'status_id' => Status::ON_HOLD,
            'technician_id' => null,
            'index' => $request->index,
        ]);
        $this->createOrderStatus($order);
        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('messages.error_updating_order')], 500);
        }

        ActionsLog::logAction('Order', 'Held', $order->id, 'Order set on hold successfully', $order->fresh()->toArray(), $oldOrder);

        // broadcast to current channels
        broadcast(new OrderUpdatedEvent(order:$order, oldTechnicianId: $oldTechnicianId))->toOthers();
        
        return new OrderResource($order->loadCount('invoices'));
    }

    public function setCancelled(Request $request, Order $order)
    {
        if(!auth()->user()->hasPermission('orders_cancel')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_cancel_orders')], 403);
        }

        if($request->reason == '') {
            return response()->json(['error' => 'Reason is required'], 400);
        }

        if ($order->status_id == Status::COMPLETED || $order->status_id == Status::CANCELLED) {
            return response()->json(['error' => __('messages.order_is_already_completed_or_cancelled')], 400);
        }

        if($order->invoices->count() > 0) {
            return response()->json(['error' => __('messages.order_has_invoices')], 400);
        }

        // get the old technician id before updating the order to broadcast to the old technician after updating the order
        $oldTechnicianId = $order->technician_id;
        $oldOrder = $order->fresh()->toArray();

        DB::beginTransaction();

        try {
            $order->update([
            'status_id' => Status::CANCELLED,
            'technician_id' => null,
            'cancelled_at' => now(),
            'reason' => $request->reason,
            ]);
            $this->createOrderStatus($order);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('messages.error_updating_order')], 500);
        }

        ActionsLog::logAction('Order', 'Cancelled', $order->id, 'Order cancelled successfully', $order->fresh()->toArray(), $oldOrder);

        // broadcast to current channels
        broadcast(new OrderUpdatedEvent(order:$order, oldTechnicianId: $oldTechnicianId))->toOthers();

        return new OrderResource($order->loadCount('invoices'));
    }

    public function setReceived(Order $order)
    {
        // check if user title_id is technician
        if(!in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_accept_this_order')], 403);
        }

        // get all in progress orders for the technician and check if the order is the first one
        $inProgressOrders = Order::query()
            ->where('technician_id', auth()->user()->id)
            ->whereIn('status_id', [Status::DESTRIBUTED, Status::RECEIVED, Status::ARRIVED])
            ->orderBy('index', 'asc')
            ->get();

        // check if the technician already has an in progress order
        if($inProgressOrders->count() == 0) {
            return response()->json(['error' => __('messages.no_in_progress_orders_found')], 400);
        }

        // check if the current order is the first one in the list of in progress orders
        if($inProgressOrders->first()->id != $order->id) {
            return response()->json(['error' => __('messages.this_is_not_your_current_order')], 400);
        }

        // check if the order status is destributed (this is the only status that can be set to received)
        if($order->status_id != Status::DESTRIBUTED) {
            return response()->json(['error' => __('messages.order_is_not_destributed')], 400);
        }

        $oldOrder = $order->fresh()->toArray();

        // update the order status to received
        try {
            $order->update(['status_id' => Status::RECEIVED]);
            $this->createOrderStatus($order);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('messages.error_updating_order')], 500);
        }
        ActionsLog::logAction('Order', 'Received', $order->id, 'Order received successfully', $order->fresh()->toArray(), $oldOrder);
        broadcast(new OrderUpdatedEvent(order:$order))->toOthers();
        return new OrderResource($order->loadCount('invoices'));
    }

    public function setArrived(Order $order)
    {
        // check if user title_id is technician
        if(!in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_arrive_this_order')], 403);
        }

        // get all in progress orders for the technician and check if the order is the first one
        $inProgressOrders = Order::query()
            ->where('technician_id', auth()->user()->id)
            ->whereIn('status_id', [Status::RECEIVED, Status::ARRIVED])
            ->orderBy('index', 'asc')
            ->get();

        // check if the technician already has an in progress order
        if($inProgressOrders->count() == 0) {
            return response()->json(['error' => __('messages.no_in_progress_orders_found')], 400);
        }

        // check if the current order is the first one in the list of in progress orders
        if($inProgressOrders->first()->id != $order->id) {
            return response()->json(['error' => __('messages.this_is_not_your_current_order')], 400);
        }

        // check if the order status is received (this is the only status that can be set to arrived)
        if($order->status_id != Status::RECEIVED) {
            return response()->json(['error' => __('messages.order_is_not_received')], 400);
        }

        $oldOrder = $order->fresh()->toArray();

        // update the order status to arrived
        try {
            $order->update(['status_id' => Status::ARRIVED]);
            $this->createOrderStatus($order);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('messages.error_updating_order')], 500);
        }
        ActionsLog::logAction('Order', 'Arrived', $order->id, 'Order arrived successfully', $order->fresh()->toArray(), $oldOrder);
        broadcast(new OrderUpdatedEvent(order:$order))->toOthers();
        return new OrderResource($order->loadCount('invoices'));
    }

    public function setCompleted(Order $order)
    {
        // check if user title_id is technician
        if(!in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_complete_this_order')], 403);
        }

        // get all in progress orders for the technician and check if the order is the first one
        $inProgressOrders = Order::query()
            ->where('technician_id', auth()->user()->id)
            ->whereIn('status_id', [Status::ARRIVED])
            ->orderBy('index', 'asc')
            ->get();

        // check if the technician already has an in progress order
        if($inProgressOrders->count() == 0) {
            return response()->json(['error' => __('messages.no_in_progress_orders_found')], 400);
        }

        // check if the current order is the first one in the list of in progress orders
        if($inProgressOrders->first()->id != $order->id) {
            return response()->json(['error' => __('messages.this_is_not_your_current_order')], 400);
        }

        // check if the order status is arrived (this is the only status that can be set to completed)
        if($order->status_id != Status::ARRIVED) {
            return response()->json(['error' => __('messages.order_is_not_arrived')], 400);
        }

        // check if the order has invoices
        if($order->invoices->count() == 0) {
            return response()->json(['error' => __('messages.order_has_no_invoices')], 400);
        }

        $oldOrder = $order->fresh()->toArray();

        DB::beginTransaction();

        // update the order status to completed
        try {
            $order->update([
                'status_id' => Status::COMPLETED,
                'completed_at' => now(),
            ]);
            $this->createOrderStatus($order);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('messages.error_updating_order')], 500);
        }
        ActionsLog::logAction('Order', 'Completed', $order->id, 'Order completed successfully', $order->fresh()->toArray(), $oldOrder);
        broadcast(new OrderUpdatedEvent(order:$order))->toOthers();
        // broadcast(new OrderCompletedEvent($order))->toOthers();
        return new OrderResource($order->loadCount('invoices'));
    }

    public function setAppointment(Request $request, Order $order)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $appointment = Carbon::parse($request->date . ' ' . $request->time);
        $oldOrder = $order->fresh()->toArray();
        $order->update(['appointment' => $appointment]);
        ActionsLog::logAction('Order', 'Appointment Set', $order->id, 'Order appointment set successfully', $order->fresh()->toArray(), $oldOrder);
        broadcast(new OrderUpdatedEvent(order:$order))->toOthers();
        return new OrderResource($order->loadCount('invoices'));
    }

    public function deleteAppointment(Order $order)
    {
        $oldOrder = $order->fresh()->toArray();
        $order->update(['appointment' => null]);
        ActionsLog::logAction('Order', 'Appointment Deleted', $order->id, 'Order appointment deleted successfully', $order->fresh()->toArray(), $oldOrder);
        broadcast(new OrderUpdatedEvent(order:$order))->toOthers();
        return new OrderResource($order->loadCount('invoices'));
    }

    public function getOrderStatuses(Order $order)
    {
        $statuses = $order->statuses->load('technician', 'creator');
        return OrderStatusResource::collection($statuses);
    }

    // Invoices
    public function getInvoices(Order $order)
    {
        if(!auth()->user()->hasPermission('orders_invoices')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_view_invoices')], 403);
        }

        $invoices = Invoice::query()
            ->where('order_id', $order->id)
            ->orderBy('id', 'desc')
            ->with('invoice_details', 'invoice_part_details', 'payments.user')
            ->get();
        return InvoiceResource::collection($invoices);
    }

    public function storeInvoice(Request $request, Order $order)
    {
        if(!auth()->user()->can('create', Invoice::class)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_create_invoice')], 403);
        }

        // check if user is a technician
        if(in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP)) {
            // this means that the user is a technician
            // so we need to check if the order is the current technician's order
            // get order with min index
            $currentTechnicianOrder = Order::query()
                ->where('technician_id', auth()->user()->id)
                ->whereIn('status_id', [Status::DESTRIBUTED, Status::RECEIVED, Status::ARRIVED])
                ->orderBy('index', 'asc')
                ->first();

            // if the order is not the current technician's order, return an error
            if($currentTechnicianOrder->id != $order->id) {
                return response()->json(['error' => __('messages.this_order_is_not_your_current_order, refresh_the_page_and_try_again')], 403);
            }
        }

        $prepared_invoice = [
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'delivery' =>  $request->delivery,
            'payment_status' => 'pending',
        ];
        
        $prepared_selected_services = collect($request->services)->map(function($row) {
            return [
                'service_id' => $row['id'], 
                'quantity' => $row['quantity'],
                'price' => $row['price'],
            ];
        })->all();

        $prepared_parts = collect($request->parts)->map(function($row) {
            return [
                'name' => $row['name'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                // 'type' => $row['type'], // Old Method with internal and external selection
                'type' => 'internal',
            ];
        })->all();

        DB::beginTransaction();

        try {

            $invoice = Invoice::create($prepared_invoice);
            $invoice->invoice_details()->createMany($prepared_selected_services);
            $invoice->invoice_part_details()->createMany($prepared_parts);

            CreateInvoiceVoucher::createVoucher($invoice);

            // Old Method with internal and external selection
            // if (collect($request->parts)->where('type', 'external')->sum('total') > 0) {
            //     CreateCostVoucher::createVoucher($invoice);
            // }

            DB::commit();
            ActionsLog::logAction('Order', 'Invoice Created', $order->id, 'Invoice created successfully', $invoice->fresh()->load('invoice_details', 'invoice_part_details')->toArray());
            broadcast(new OrderInvoiceCreatedEvent($invoice))->toOthers();
            broadcast(new OrderUpdatedEvent(order:$order))->toOthers();

            return response()->json(['success' => 'Invoice created successfully']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateInvoice(Request $request, Order $order, Invoice $invoice)
    {
        // This update means that user is applying discount to the invoice 
        // because this system has no invoice update except for discount

        // check if user has permission to edit invoice
        if(!auth()->user()->can('discount', $invoice)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_apply_discount')], 403);
        }

        // check if invoice date is today
        if($invoice->created_at->endOfDay()->addHours(4)->isPast()) {
            return response()->json(['error' => __('messages.you_cant_apply_discount_to_old_invoices')], 400);
        }

        // check if discount is negative
        if($request->discount < 0) {
            return response()->json(['error' => __('messages.discount_cannot_be_negative')], 400);
        }

        // check if invoice has no payments
        if($invoice->payments->count() > 0) {
            return response()->json(['error' => __('messages.invoice_has_payments_you_cant_apply_discount')], 400);
        }

        // get invoice services amount from invoice details
        $invoice_services_amount = InvoiceDetails::query()
            ->where('invoice_id', $invoice->id)
            ->whereHas('service', function($query) {
                $query->where('type', 'service');
            })
            ->sum(DB::raw('quantity * price'));

        // check if discount is greater than the invoice services amount
        if((float)$request->discount > (float)$invoice_services_amount) {
            return response()->json(['error' => __('messages.discount_cannot_be_greater_than_the_invoice_services_amount')], 400);
        }

        $oldInvoice = $invoice->fresh()->toArray();

        DB::beginTransaction();
        try {

            $invoice->update(['discount' => $request->discount]);

            InvoiceService::rePostInvoice($order, $invoice);

            DB::commit();


            ActionsLog::logAction('Invoice', 'Discount Updated', $invoice->id, 'Invoice discount updated successfully', $invoice->fresh()->toArray(), $oldInvoice);
            
            broadcast(new OrderInvoiceUpdatedEvent($invoice))->toOthers();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return new InvoiceResource($invoice->load('invoice_details', 'invoice_part_details', 'payments.user'));
    }

    public function showInvoice(Order $order, Invoice $invoice)
    {
        $invoice = Invoice::query()
            ->with([
                'order' => [
                    'customer',
                    'phone',
                    'address',
                    'department',
                ],
                'invoice_details',
                'invoice_part_details',
                'payments.user',
                'reconciliations'
            ])
            ->withCount('attachments')
            ->with('order', function($query) {
                $query->withCount('invoices');
            })
            ->findOrFail($invoice->id);
        return new InvoiceResource($invoice);
    }

    public function destroyInvoice(Order $order, Invoice $invoice)
    {
        // check if user has permission to delete invoice
        if(!auth()->user()->hasPermission('invoices_delete')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_delete_this_invoice')], 403);
        }

        // check if invoice has attachments
        if($invoice->attachments->count() > 0) {
            return response()->json(['error' => __('messages.invoice_has_attachments_you_cant_delete_it')], 400);
        }

        // check if order has only one invoice
        if($order->invoices->count() == 1) {
            return response()->json(['error' => __('messages.order_has_only_one_invoice_you_cant_delete_it')], 400);
        }

        // check if invoice has reconciliations
        if($invoice->reconciliations->count() > 0) {
            return response()->json(['error' => __('messages.invoice_has_reconciliations_you_cant_delete_it')], 400);
        }

        // check if invoice has collected payments
        if($invoice->payments->where('is_collected', true)->count() > 0) {
            return response()->json(['error' => __('messages.invoice_has_collected_payments_you_cant_delete_it')], 400);
        }

        $oldInvoice = $invoice->fresh()->toArray();

        DB::beginTransaction();
        try {

            // get all related vouchers of the invoice
            $vouchers = Voucher::where('invoice_id', $invoice->id)->get();

            // force delete all voucher details of the vouchers
            VoucherDetail::whereIn('voucher_id', $vouchers->pluck('id'))->forceDelete();

            // force delete all vouchers of the invoice
            foreach ($vouchers as $voucher) {
                $voucher->forceDelete();
            }

            // delete all payments of the invoice
            $invoice->payments()->delete();

            // Soft delete invoice
            $invoice->delete(); // this is soft delete

            DB::commit();
            ActionsLog::logAction('Order', 'Invoice Deleted', $invoice->id, 'Invoice deleted successfully', $invoice->fresh()->toArray(), $oldInvoice);
            broadcast(new OrderInvoiceDeletedEvent($order))->toOthers();
            return response()->json(['success' => 'Invoice deleted successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function getDepartmentServices(Order $order)
    {
        $services = Service::query()
            ->where('department_id', $order->department_id)
            ->where('active', 1)
            ->where('type','service')
            ->get();
        return ServiceResource::collection($services);
    }

    // Payments
    public function getPayments(Order $order, Invoice $invoice)
    {
        return Payment::where('invoice_id', $invoice->id)->get();
    }

    public function storePayment(Request $request, Order $order, Invoice $invoice)
    {
        // check if user has permission to create payment
        if(!auth()->user()->hasPermission('payments_create')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_create_payment')], 403);
        }

        // check if user is a technician
        if(in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP)) {
            // this means that the user is a technician
            // so we need to check if the order is the current technician's order
            // get order with min index
            $currentTechnicianOrder = Order::query()
                ->where('technician_id', auth()->user()->id)
                ->whereIn('status_id', [Status::DESTRIBUTED, Status::RECEIVED, Status::ARRIVED])
                ->orderBy('index', 'asc')
                ->first();

            // if the order is not the current technician's order, return an error
            if($currentTechnicianOrder->id != $order->id) {
                return response()->json(['error' => __('messages.this_order_is_not_your_current_order, refresh_the_page_and_try_again')], 403);
            }
        }

        // check if amount is negative or zero
        if($request->amount <= 0) {
            return response()->json(['error' => __('messages.amount_cannot_be_negative_or_zero')], 400);
        }

        // check if amount is greater than the invoice remaining amount 
        if((float)$request->amount > (float)$invoice->remaining_amount) {
            return response()->json(['error' => __('messages.amount_cannot_be_greater_than_the_invoice_remaining_amount')], 400);
        }

        $request['user_id'] = auth()->id();
        $request['is_collected'] = false;
        $request['invoice_id'] = $invoice->id;

        $payment = Payment::create($request->all());
        ActionsLog::logAction('Invoice', 'Payment Created', $invoice->id, 'Invoice payment created successfully', $payment->fresh()->toArray());
        broadcast(new InvoicePaymentsUpdatedEvent($invoice))->toOthers();
        return new InvoiceResource($invoice->load('invoice_details', 'invoice_part_details', 'payments.user'));
    }

    public function showPayment(Order $order, Invoice $invoice, Payment $payment)
    {
        return $payment;
    }

    public function destroyPayment(Order $order, Invoice $invoice, Payment $payment)
    {
        // check if user has permission to delete payment
        if(!auth()->user()->hasPermission('payments_delete')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_delete_this_payment')], 403);
        }

        // check if payment is not collected
        if ($payment->is_collected) {
            return response()->json(['error' => __('messages.payment_is_already_collected_you_cant_delete_it')], 400);
        }

        $oldPayment = $payment->fresh()->toArray();
        DB::beginTransaction();
        try {
                foreach ($payment->vouchers()->withTrashed()->get() as $voucher) {
                    foreach($voucher->voucherDetails()->withTrashed()->get() as $row){
                        $row->forceDelete();
                    }
                    $voucher->forceDelete();
                }
                $payment->delete(); // Observer Applied
                DB::commit();
                ActionsLog::logAction('Invoice', 'Payment Deleted', $invoice->id, 'Invoice payment deleted successfully', [], $oldPayment);
                broadcast(new InvoicePaymentsUpdatedEvent($invoice))->toOthers();
                return new InvoiceResource($invoice->load('invoice_details', 'invoice_part_details', 'payments.user'));
            } catch (\Exception $e) {
                DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Comments
    public function getComments(Order $order)
    {
        $comments = $order->comments->load('user');
        return CommentResource::collection($comments);
    }

    public function storeComment(Request $request, Order $order)
    {

        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        if(in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP)) {
            // this means that the user is a technician
            // so we need to check if the order is the current technician's order
            // get order with min index
            $currentTechnicianOrder = Order::query()
                ->where('technician_id', auth()->user()->id)
                ->whereIn('status_id', [Status::DESTRIBUTED, Status::RECEIVED, Status::ARRIVED])
                ->orderBy('index', 'asc')
                ->first();

            // if the order is not the current technician's order, return an error
            if($currentTechnicianOrder->id != $order->id) {
                return response()->json(['error' => __('messages.this_order_is_not_your_current_order, refresh_the_page_and_try_again')], 403);
            }
        }

        $comment = Comment::create([
            'order_id' => $order->id,
            'comment' => $request->comment,
            'user_id' => auth()->id(),
        ]);

        ActionsLog::logAction('Order', 'Comment Created', $order->id, 'Order comment created successfully', $comment->fresh()->toArray());

        broadcast(new OrderCommentCreatedEvent($comment))->toOthers();
        return new CommentResource($comment->load('user'));
    }

    public function showComment(Order $order, Comment $comment)
    {
        return new CommentResource($comment->load('user'));
    }

    public function exportToExcel(Request $request)
    {
        $orders = Order::query()
        ->with('phone')
        ->with('address')
        ->with('customer')
        ->with('creator')
        ->with('technician')
        ->with('status')
        ->with('department')
        ->with('invoices')
        ->with('invoices.invoice_details')
        ->with('invoices.invoice_part_details')
        ->with('invoices.payments')
        ->when($request->customer_name, function($query) use ($request) {
            $query->whereRelation('customer', 'name', 'like', '%' . $request->customer_name . '%');
        })
        ->when($request->customer_phone, function($query) use ($request) {
            $query->whereRelation('phone', 'number', 'like', '%' . $request->customer_phone . '%');
        })
        ->when($request->area_ids, function($query) use ($request) {
            $query->whereHas('address', function($query) use ($request) {
                $query->whereIn('area_id', $request->area_ids);
            });
        })
        ->when($request->block, function($query) use ($request) {
            $query->whereRelation('address', 'block', 'like', '%' . $request->block . '%');
        })
        ->when($request->street, function($query) use ($request) {      
            $query->whereRelation('address', 'street', 'like', '%' . $request->street . '%');
        })
        ->when($request->order_number, function($query) use ($request) {
            $query->where('id', '=', $request->order_number);    
        })
        ->when($request->creator_ids, function($query) use ($request) {
            $query->whereIn('created_by', $request->creator_ids);
        })
        ->when($request->status_ids, function($query) use ($request) {
            $query->whereIn('status_id', $request->status_ids);
        })
        ->when($request->technician_ids, function($query) use ($request) {
            $query->whereIn('technician_id', $request->technician_ids);
        })
        ->when($request->department_ids, function($query) use ($request) {
            $query->whereIn('department_id', $request->department_ids);
        })
        ->when($request->tags, function($query) use ($request) {    
            $query->where('tags', 'like', '%' . $request->tags . '%');
        })
        ->when($request->start_created_at, function($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->start_created_at);
        })
        ->when($request->end_created_at, function($query) use ($request) {  
            $query->whereDate('created_at', '<=', $request->end_created_at);
        })
        ->when($request->start_completed_at, function($query) use ($request) {
            $query->whereDate('completed_at', '>=', $request->start_completed_at);
        })
        ->when($request->end_completed_at, function($query) use ($request) {    
            $query->whereDate('completed_at', '<=', $request->end_completed_at);
        })
        ->when($request->start_cancelled_at, function($query) use ($request) {
            $query->whereDate('cancelled_at', '>=', $request->start_cancelled_at);
        })
        ->when($request->end_cancelled_at, function($query) use ($request) {        
            $query->whereDate('cancelled_at', '<=', $request->end_cancelled_at);
        })
        ->orderBy('id','desc');

        $orders_count = $orders->count();
        if($orders_count > 5000) {
            return response()->json(['error' => __('messages.you_can_only_export_up_to_5000_records')], 400);
        }

        $log_data = [
            'filters' => $request->all(),
            'count' => $orders_count,
        ];
        // action log
        ActionsLog::logAction('Order', 'Export To Excel', 0, 'Order exported to excel successfully', $log_data);

        return Excel::download(new OrdersExport('pages.orders.excel', 'Orders', $orders->get()), 'Orders.xlsx');
    }

    private function createOrderStatus(Order $order)
    {
        OrderStatus::create([
            'order_id' => $order->id,
            'status_id' => $order->status_id,
            'technician_id' => $order->technician_id,
            'reason' => $order->reason,
            'user_id' => auth()->id(),
        ]);
    }

}
