<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Status;
use App\Http\Resources\OrderResource;
use App\Models\Title;

class TechnicianPageController extends Controller
{
    public function index()
    {
        // abort if user is not a technician
        if(!in_array(auth()->user()->title_id, Title::TECHNICIANS_GROUP)) {
            abort(403);
        }
        return view('pages.technician.index');
    }

    public function getCurrentOrderForTechnician()
    {
        // get order with min index
        $order = Order::query()
            ->where('technician_id', auth()->user()->id)
            ->whereIn('status_id', [Status::DESTRIBUTED, Status::RECEIVED, Status::ARRIVED])
            ->orderBy('index', 'asc')
            ->first();

            
        if(!$order) {
            return response()->json(['data' => null]);
        }
        
        $order
            ->load('status', 'department', 'technician', 'customer', 'phone', 'address')
            ->loadCount('invoices')
            ->load('invoices.invoice_details', 'invoices.invoice_part_details', 'invoices.payments.user');
        return new OrderResource($order);
    }
}
