<?php

namespace App\Livewire\AccountingReports;

use App\Models\Department;
use App\Models\Status;
use App\Models\Title;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

use function Laravel\Prompts\select;

class TargetStatement extends Component
{

    public $month;
    public $year;
    public function mount()
    {
        $this->month = "05";
        // $this->month = now()->format('m');
        $this->year = now()->format('Y');
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->where('is_service', 1)

            ->with('technicians', function ($q) {

                $q->with('title:id,name_en,name_ar');

                $q->withCount(['orders_technician as discount_amount_sum' => function ($q) {
                    $q->join('invoices', 'orders.id', '=', 'invoices.order_id');
                    $q->whereMonth('orders.created_at',$this->month);
                    $q->whereYear('orders.created_at',$this->year);
                    $q->select(DB::raw('SUM(invoices.discount)'));
                }]);

                $q->withCount(['orders_technician as delivery_amount_sum' => function ($q) {
                    $q->join('invoices', 'orders.id', '=', 'invoices.order_id');
                    $q->whereMonth('orders.created_at',$this->month);
                    $q->whereYear('orders.created_at',$this->year);
                    $q->select(DB::raw('SUM(invoices.delivery)'));
                }]);

                $q->withCount(['orders_technician as invoice_details_services_amount_sum' => function ($q) {
                    $q->join('invoices', 'orders.id', '=', 'invoices.order_id');
                    $q->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id');
                    $q->join('services', 'invoice_details.service_id', '=', 'services.id');
                    $q->where('services.type','service');
                    $q->whereMonth('orders.created_at',$this->month);
                    $q->whereYear('orders.created_at',$this->year);
                    $q->select(DB::raw('SUM(invoice_details.quantity * invoice_details.price)'));
                }]);

                $q->withCount(['orders_technician as invoice_details_parts_amount_sum' => function ($q) {
                    $q->join('invoices', 'orders.id', '=', 'invoices.order_id');
                    $q->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id');
                    $q->join('services', 'invoice_details.service_id', '=', 'services.id');
                    $q->where('services.type','part');
                    $q->whereMonth('orders.created_at',$this->month);
                    $q->whereYear('orders.created_at',$this->year);
                    $q->select(DB::raw('SUM(invoice_details.quantity * invoice_details.price)'));
                }]);

                $q->withCount(['orders_technician as invoice_part_details_amount_sum' => function ($q) {
                    $q->join('invoices', 'orders.id', '=', 'invoices.order_id');
                    $q->join('invoice_part_details', 'invoices.id', '=', 'invoice_part_details.invoice_id');
                    $q->whereMonth('orders.created_at',$this->month);
                    $q->whereYear('orders.created_at',$this->year);
                    $q->select(DB::raw('SUM(invoice_part_details.quantity * invoice_part_details.price)'));
                }]);

                $q->withCount(['targets as invoices_target_sum' => function ($q) {
                    $q->where('month',$this->month);
                    $q->where('year',$this->year);
                    $q->select(DB::raw('SUM(invoices_target)'));
                }]);
                $q->withCount(['targets as amount_target_sum' => function ($q) {
                    $q->where('month',$this->month);
                    $q->where('year',$this->year);
                    $q->select(DB::raw('SUM(amount_target)'));
                }]);

                $q->withCount(['orders_technician as completed_orders_count' => function ($q) {
                    $q->where('status_id', Status::COMPLETED);
                    $q->whereMonth('completed_at', $this->month);
                    $q->whereYear('completed_at', $this->year);
                }]);

                $q->whereHas('targets',function($q){
                    $q->where('month',$this->month);
                    $q->where('year',$this->year);
                });
            })
            ->get();
    }

    #[Computed()]
    public function titles()
    {
        return Title::query()
            ->whereIn('id', Title::TECHNICIANS_GROUP)
            ->get();
    }

    public function render()
    {
        return view('livewire.accounting-reports.target-statement');
    }
}
