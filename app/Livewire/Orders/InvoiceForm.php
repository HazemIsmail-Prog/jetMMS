<?php

namespace App\Livewire\Orders;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class InvoiceForm extends Component
{
    public $showForm = false;
    public Order $order;
    public $search = '';
    #[Rule('required')]
    public $selected_services = [];
    public $select_service = [];


    #[On('showInvoiceForm')]
    public function showInvoiceForm() {
        $this->showForm = true;
    }

    public function hideInvoiceForm()
    {
        $this->reset('select_service', 'selected_services', 'search');
        $this->showForm = false;
    }

    #[Computed]
    public function services()
    {
        return
            Service::query()
            ->where('department_id', $this->order->department_id)
            ->when($this->search, function ($q) {
                $q->where('name_en', 'like', '%' . $this->search . '%');
                $q->orWhere('name_ar', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function updatedSelectedServices($val, $key)
    {
        $index = explode('.', $key)[0];
        if ($this->selected_services[$index]['quantity'] && $this->selected_services[$index]['price']) {
            $this->selected_services[$index]['service_total'] = $this->selected_services[$index]['quantity'] * $this->selected_services[$index]['price'];
        } else {
            $this->selected_services[$index]['service_total'] = 0;
        }
    }

    public function updatedSelectService($val, $key)
    {
        if ($val) {
            $service = $this->services->where('id', $key)->first();
            $this->selected_services[$key] = [
                'service_id' => $key,
                'service_type' => $service->type,
                'name' => $service->name,
                'min_price' => $service->min_price,
                'max_price' => $service->max_price,
                'quantity' => '',
                'price' => '',
                'service_total' => 0,
            ];
        } else {
            unset($this->selected_services[$key]);
        }
    }

    public function delete_service($service_id)
    {
        unset($this->select_service[$service_id]);
        unset($this->selected_services[$service_id]);
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'order_id' => $this->order->id,
                'user_id' => auth()->id(),
                'payment_status' => collect($this->selected_services)->sum('service_total') > 0 ? 'pending' : 'free',
            ]);
            foreach ($this->selected_services as $row) {
                $invoice->invoice_details()->create([
                    'service_id' => $row['service_id'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                ]);
            }
            DB::commit();
            $this->reset('select_service', 'selected_services', 'search', 'showForm');
            $this->dispatch('invoiceCreated');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.orders.invoice-form');
    }
}
