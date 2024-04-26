<?php

namespace App\Livewire\Orders\Invoices;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Services\CreateCostVoucher;
use App\Services\CreateInvoiceVoucher;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceForm extends Component
{
    public $showModal = false;
    public Order $order;
    public $search = '';
    public $selected_services = [];
    public $select_service = [];
    public $delivery = 0;
    public $parts = [];

    public function rules()
    {
        return [
            'selected_services' => 'required_without_all:parts',
            'parts' => 'required_without_all:selected_services',
        ];
    }

    #[On('showInvoiceFormModal')]
    public function show(Order $order)
    {
        $this->search = '';
        $this->select_service = [];
        $this->selected_services = [];
        $this->delivery = 0;
        $this->parts = [];
        $this->showModal = true;
        $this->order = $order;
    }

    public function updatedParts($val, $key)
    {
        $index = explode('.', $key)[0];
        if ($this->parts[$index]['quantity'] && $this->parts[$index]['price']) {
            $this->parts[$index]['total'] = $this->parts[$index]['quantity'] * $this->parts[$index]['price'];
        } else {
            $this->parts[$index]['total'] = 0;
        }
    }

    public function addPartRow()
    {
        $this->parts[] = [
            'name' => '',
            'quantity' => '',
            'price' => '',
            'total' => 0,
            'type' => '',
        ];
    }

    public function deletePartRow($index)
    {
        unset($this->parts[$index]);
    }

    public function hideInvoiceForm()
    {
        $this->reset('select_service', 'selected_services', 'search');
        $this->showModal = false;
    }

    #[Computed]
    public function services()
    {
        return
            Service::query()
            ->where('active', true)
            ->where('department_id', $this->order->department_id)
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('name_en', 'like', '%' . $this->search . '%');
                    $q->orWhere('name_ar', 'like', '%' . $this->search . '%');
                });
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
                'delivery' => $this->delivery,
                'payment_status' => collect($this->selected_services)->sum('service_total') > 0 ? 'pending' : 'free',
            ]);  // Observer Applied
            foreach ($this->selected_services as $row) {
                $invoice->invoice_details()->create([
                    'service_id' => $row['service_id'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                ]);
            }
            foreach ($this->parts as $row) {
                $invoice->invoice_part_details()->create([
                    'name' => $row['name'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'type' => $row['type'],
                ]);
            }
            CreateInvoiceVoucher::createVoucher($invoice);

            if (collect($this->parts)->where('type', 'external')->sum('total') > 0) {
                CreateCostVoucher::createVoucher($invoice);
            }
            DB::commit();
            $this->reset('select_service', 'selected_services', 'search', 'showModal');
            $this->dispatch('invoicesUpdated');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.orders.invoices.invoice-form');
    }
}
