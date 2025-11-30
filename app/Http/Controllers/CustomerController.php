<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Status;
use App\Models\Department;
use App\Models\Order;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\OrderResource;
use App\Events\CustomerCreatedEvent;
use App\Events\CustomerDeletedEvent;
use App\Events\CustomerUpdatedEvent;
use App\Services\ActionsLog;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Title;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('viewAny', Customer::class), 403);
        if ($request->wantsJson()) {
            $customers = Customer::query()
                ->with('phones')
                ->with('addresses')
                ->with('orders.invoices.invoice_details')
                ->with('orders.invoices.invoice_part_details')
                ->with('orders.invoices.payments')
                ->withCount('contracts')
                ->withCount('orders')
                ->withCount(['orders as in_progress_orders_count' => function($query) {
                    $query->whereIn('status_id', [Status::CREATED, Status::DESTRIBUTED, Status::ON_HOLD, Status::RECEIVED, Status::ARRIVED]);
                }])
                ->when($request->name, function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                })
                ->when($request->phone, function($query) use ($request) {
                    $query->whereRelation('phones', 'number', 'like', '%' . $request->phone . '%');
                })
                ->when($request->area_ids, function($query) use ($request) {
                    $query->whereHas('addresses.area', function($q) use ($request) {
                        $q->whereIn('id', $request->area_ids);
                    });
                })
                ->when($request->block, function($query) use ($request) {
                    $query->whereRelation('addresses', 'block', 'like', '%' . $request->block . '%');
                })
                ->when($request->street, function($query) use ($request) {
                    $query->whereRelation('addresses', 'street', 'like', '%' . $request->street . '%');
                })
                ->when($request->building, function($query) use ($request) {
                    $query->whereRelation('addresses', 'building', 'like', '%' . $request->building . '%');
                })
                ->when($request->start_created_at, function($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_created_at);
                })
                ->when($request->end_created_at, function($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_created_at);
                })
                ->orderBy('id', 'desc')
                ->paginate(10);
            return CustomerResource::collection($customers);
        }

        return view('pages.customers.index',[
            'departments' => DepartmentResource::collection(Department::where('active', 1)->where('is_service', 1)->orderByDesc('position')->get()),
        ]);
    }

    public function store(Request $request)
    {
        // check if user has permission to create customer
        if(!auth()->user()->can('create', Customer::class)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_create_customer')], 403);
        }

        $validatedCustomerData = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255'
        ]);

        $validatedPhonesData = $request->validate([
            'phones' => 'required|array',
            'phones.*.number' => 'required|string|max:255|unique:phones,number',
            'phones.*.type' => 'required|string|max:255',
        ]);

        $validatedAddressesData = $request->validate([
            'addresses' => 'required|array',
            'addresses.*.area_id' => 'required|exists:areas,id',
            'addresses.*.block' => 'required|string|max:255',
            'addresses.*.street' => 'required|string|max:255',
            'addresses.*.jadda' => 'nullable|string|max:255',
            'addresses.*.building' => 'nullable|string|max:255',
            'addresses.*.floor' => 'nullable|string|max:255',
            'addresses.*.apartment' => 'nullable|string|max:255',
            'addresses.*.notes' => 'nullable|string|max:255',
        ]);

        $validatedCustomerData['active'] = true;
        $validatedCustomerData['created_by'] = auth()->id();
        $validatedCustomerData['updated_by'] = auth()->id();

        DB::beginTransaction();
        try {

            $customer = Customer::create($validatedCustomerData);
            $customer->phones()->createMany($validatedPhonesData['phones']);
            $customer->addresses()->createMany($validatedAddressesData['addresses']);
            DB::commit();
            ActionsLog::logAction('Customer', 'Created', $customer->id, 'Customer created successfully', $customer->load('phones', 'addresses.area')->toArray());
            broadcast(new CustomerCreatedEvent($customer))->toOthers();
            return $this->getCustomerResource($customer);   
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Customer $customer)
    {
        // check if user has permission to delete customer
        if(!auth()->user()->can('delete', $customer)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_delete_this_customer')], 403);
        }

        // check if customer has orders
        if($customer->orders->count() > 0) {
            return response()->json(['error' => __('messages.customer_has_orders_you_cant_delete_it')], 400);
        }

        $oldCustomer = $customer->load('phones', 'addresses')->toArray();

        DB::beginTransaction();
        try {
            $customer->phones()->delete();
            $customer->addresses()->delete();
            $customer->delete();
            DB::commit();
            ActionsLog::logAction('Customer', 'Deleted', $customer->id, 'Customer deleted successfully', $customer->toArray(), $oldCustomer);
            broadcast(new CustomerDeletedEvent($customer))->toOthers();
            return response()->json(['success' => 'Customer deleted successfully']);
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Customer $customer)
    {

        // check if user has permission to update customer
        if(!auth()->user()->can('update', $customer)) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_update_this_customer')], 403);
        }

        $validatedCustomerData = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255'
        ]);

        $validatedPhonesData = $request->validate([
            'phones' => 'required|array',       
            'phones.*.id' => 'nullable',
            'phones.*.number' => ['required', 'string', 'max:255', function($attribute, $value, $fail) use($request) {
                $index = explode('.', $attribute)[1];
                $id = $request->input("phones.{$index}.id");
                $exists = \App\Models\Phone::where('number', $value)
                    ->where('id', '!=', $id)
                    ->exists();
                if ($exists) {
                    $fail('The phone number has already been taken.');
                }
            }],
            'phones.*.type' => 'required|string|max:255',
        ]);

        $validatedAddressesData = $request->validate([
            'addresses' => 'required|array',
            'addresses.*.id' => 'nullable',
            'addresses.*.area_id' => 'required|exists:areas,id',
            'addresses.*.block' => 'required|string|max:255',
            'addresses.*.street' => 'required|string|max:255',
            'addresses.*.jadda' => 'nullable|string|max:255',
            'addresses.*.building' => 'nullable|string|max:255',
            'addresses.*.floor' => 'nullable|string|max:255',
            'addresses.*.apartment' => 'nullable|string|max:255',
            'addresses.*.notes' => 'nullable|string|max:255',
        ]);

        $validatedCustomerData['updated_by'] = auth()->id();

        $oldCustomer = $customer->load('phones', 'addresses')->toArray();
        DB::beginTransaction();
        try {
            $customer->update($validatedCustomerData);
            // delete all phones and addresses only does not have orders
            $customer->phones()->whereDoesntHave('orders')->delete();
            $customer->addresses()->whereDoesntHave('orders')->whereDoesntHave('contracts')->delete();
            foreach($validatedPhonesData['phones'] as $phone) {
                $customer->phones()->updateOrCreate(['id' => $phone['id']],$phone);
            }
            foreach($validatedAddressesData['addresses'] as $address) {
                $customer->addresses()->updateOrCreate(['id' => $address['id']],$address);
            }
            
            DB::commit();
            ActionsLog::logAction('Customer', 'Updated', $customer->id, 'Customer updated successfully', $customer->load('phones', 'addresses')->toArray(), $oldCustomer);
            broadcast(new CustomerUpdatedEvent($customer))->toOthers();
            return $this->getCustomerResource($customer);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Customer $customer)
    {
        return $this->getCustomerResource($customer);
    }

    public function getDepartmentInProgressOrders(Customer $customer, Department $department)
    {
        $in_progress_orders = $customer->orders()
        ->where('department_id', $department->id)
        ->whereIn('status_id', [Status::CREATED, Status::DESTRIBUTED, Status::ON_HOLD, Status::RECEIVED, Status::ARRIVED])
        ->get();

        return response()->json(['in_progress_orders' => $in_progress_orders]);
    }

    public function getInProgressOrders(Customer $customer)
    {
        $in_progress_orders = Order::query()
        ->with('department')
        ->with('customer')
        ->with('phone')
        ->with('address')
        ->with('creator')
        ->with('technician')
        ->with('status')
        ->withCount('invoices')
        ->with('invoices.invoice_details')
        ->with('invoices.invoice_part_details')
        ->with('invoices.payments.user')
        ->where('customer_id', $customer->id)
        ->whereIn('status_id', [Status::CREATED, Status::DESTRIBUTED, Status::ON_HOLD, Status::RECEIVED, Status::ARRIVED])
        ->orderBy('id', 'desc')
        ->get();

        return OrderResource::collection($in_progress_orders);
    }

    public function getAllOrders(Customer $customer)
    {
        $orders = Order::query()
        ->with('department')
        ->with('customer')
        ->with('phone')
        ->with('address')
        ->with('creator')
        ->with('technician')
        ->with('status')
        ->withCount('invoices')
        ->with('invoices.invoice_details')
        ->with('invoices.invoice_part_details')
        ->with('invoices.payments.user')
        ->where('customer_id', $customer->id)
        ->orderBy('id', 'desc')
        ->get();

        return OrderResource::collection($orders);
    }

    private function getCustomerResource(Customer $customer)
    {
        $customerResource = new CustomerResource(
            $customer
                ->load('phones')
                ->load('addresses.area')
                ->load('orders.invoices.invoice_details')
                ->load('orders.invoices.invoice_part_details')
                ->load('orders.invoices.payments')
                ->loadCount('contracts')
                ->loadCount('orders')
                ->loadCount(['orders as in_progress_orders_count' => function($query) {
                    $query->whereIn('status_id', [Status::CREATED, Status::DESTRIBUTED, Status::ON_HOLD, Status::RECEIVED, Status::ARRIVED]);
                }])
            );
        return $customerResource;
    }

    public function getAvailableTechnicians(Department $department)
    {
        $technicians = User::query()
            ->where('active', 1)
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->where('department_id', $department->id)
            ->whereDoesntHave('orders_technician', function($query) {
                $query->whereIn('status_id', [Status::CREATED, Status::DESTRIBUTED, Status::RECEIVED, Status::ARRIVED]);
            })
            ->orderBy('name_ar', 'asc')
            ->get();

        return UserResource::collection($technicians);
    }
}
