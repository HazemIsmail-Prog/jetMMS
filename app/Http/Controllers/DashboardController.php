<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function ordersChart($year)
    {
        $selectedYear = $year;

        $ordersData = Order::whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month, count(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        $completedOrdersData = Order::whereYear('completed_at', $selectedYear)
            ->selectRaw('MONTH(completed_at) as month, count(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        $years = Order::distinct()->selectRaw('YEAR(created_at) as year')->pluck('year');

        $labels = ['يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر'];

        $ordersCount = array_map(fn($month) => $ordersData->get($month, 0), range(1, 12));
        $completedOrdersCount = array_map(fn($month) => $completedOrdersData->get($month, 0), range(1, 12));

        return response()->json([
            'years' => $years,
            'selectedYear' => $selectedYear,
            'ordersCountLabel' => __('messages.orders_per_month'),
            'completedOrdersCountLabel' => __('messages.completed_orders_per_month'),
            'labels' => $labels,
            'ordersCount' => $ordersCount,
            'completedOrdersCount' => $completedOrdersCount,
        ]);
    }

    public function customersChart($year)
    {
        $selectedYear = $year;

        $customersData = Customer::whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month, count(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        $years = Customer::distinct()->whereNotNull('created_at')->selectRaw('YEAR(created_at) as year')->pluck('year');

        $labels = ['يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر'];

        $customersCount = array_map(fn($month) => $customersData->get($month, 0), range(1, 12));

        return response()->json([
            'years' => $years,
            'selectedYear' => $selectedYear,
            'customersCountLabel' => __('messages.customers'),
            'labels' => $labels,
            'customersCount' => $customersCount,
        ]);
    }

    public function technicianCompletionAverage($year)
    {
        $selectedYear = $year;

        // Fetch the data for orders, technicians, and average completion
        $ordersData = Order::query()
            ->where('status_id', Status::COMPLETED)
            ->whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month, count(*) as count, COUNT(DISTINCT technician_id) as totalTechnicians, ROUND(COUNT(*)/NULLIF(COUNT(DISTINCT technician_id), 0), 2) as average')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month'); // Key the collection by 'month' for easy access

        // Retrieve the distinct years
        $years = Order::distinct()->selectRaw('YEAR(created_at) as year')->pluck('year');

        // Labels for months
        $labels = ['يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر'];

        // Prepare data arrays for response
        $ordersCount = [];
        $totalTechnicians = [];
        $average = [];

        foreach (range(1, 12) as $month) {
            $data = $ordersData->get($month, ['count' => 0, 'totalTechnicians' => 0, 'average' => 0]);
            $ordersCount[] = $data['count'];
            $totalTechnicians[] = $data['totalTechnicians'];
            $average[] = $data['average'];
        }

        return response()->json([
            'years' => $years,
            'selectedYear' => $selectedYear,
            'ordersCountLabel' => __('messages.average_completed_orders_for_technicians'),
            'labels' => $labels,
            'ordersCount' => $ordersCount,
            'totalTechnicians' => $totalTechnicians,
            'average' => $average,
        ]);
    }

    public function dailyStatistics()
    {

        $completedOrdersCount = Order::select('id')->whereDate('completed_at', today())->count();

        $cancelledOrdersCount = Order::select('id')->whereDate('cancelled_at', today())->count();

        $customersCount = Customer::select('id')->whereDate('created_at', today())->count();

        return response()->json([
            'completedOrdersCount' => $completedOrdersCount,
            'cancelledOrdersCount' => $cancelledOrdersCount,
            'customersCount' => $customersCount,
        ]);
    }

    public function ordersStatusCounter(Request $request)
    {
        $selectedDate = $request->input('selectedDate'); //12-2024

        $selectedMonth = explode('-', $selectedDate)[0];
        $selectedYear = explode('-', $selectedDate)[1];

        // Retrieve all statuses, ordered by index
        $statuses = Status::orderBy('index')->get();

        // Get unique year-month combinations for the date filter
        $dateFilter = Order::query()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        // Count orders grouped by date and status for the current month
        $counters = Order::query()
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, status_id')
            ->groupByRaw('DATE(created_at), status_id')
            ->orderByDesc('date')
            ->get();

        $counters = $counters->groupBy('date')->map(function ($group) {
            return $group->keyBy('status_id')->map->count->all();
        });

        // Return the data as JSON
        return response()->json([
            'statuses' => $statuses,
            'dateFilter' => $dateFilter,
            'counters' => $counters,
        ]);
    }
}