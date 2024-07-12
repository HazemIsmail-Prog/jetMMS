<table>
    <thead>
        <tr>
            <th>{{ __('messages.technician')}}</th>
            <th>{{ __('messages.department')}}</th>
            <th>{{ __('messages.title')}}</th>
            <th>{{ __('messages.shift')}}</th>
            <th>{{ __('messages.completed_orders') }}</th>
            <th>{{ __('messages.required_orders_target') }}</th>
            <th>{{ __('messages.orders_shortage') }}</th>
            <th>{{ __('messages.service_total') }}</th>
            <th>{{ __('messages.required_services_amount_target') }}</th>
            <th>{{ __('messages.services_amount_shortage') }}</th>
            <th>{{ __('messages.done_percentage') }}</th>
            <th>{{ __('messages.shortage_percentage') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            @foreach ($row->technicians as $technician)
            <tr>
                @php
                $data = $technician;
                $servicesAmount = $data->invoice_details_services_amount_sum;
                $discountAmount = $data->discount_amount_sum;
                $servicesAfterDiscount = $servicesAmount - $discountAmount;
                $invoice_details_parts_amount_sum = $data->invoice_details_parts_amount_sum;
                $invoice_part_details_amount_sum = $data->invoice_part_details_amount_sum;
                $partsAmount = $invoice_details_parts_amount_sum + $invoice_part_details_amount_sum;
                $deliveryAmount = $data->delivery_amount_sum;
                $totalIncome = $servicesAfterDiscount + $partsAmount + $deliveryAmount;
                $completedOrdersCount = $data->completed_orders_count;
                $requiredOrdersTarget = $data->invoices_target_sum;
                $ordersShortage = $requiredOrdersTarget - $completedOrdersCount;
                $requiredServicesAmountTarget = $data->amount_target_sum;
                $servicesAmountShortage = $requiredServicesAmountTarget - $totalIncome;
                $donePercentage = $requiredServicesAmountTarget > 0 ? round($servicesAfterDiscount /
                $requiredServicesAmountTarget * 100 ,2) : 0;
                $shortagePercentage = 100 - $donePercentage;
                @endphp

                <td>{{ $technician->name }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $technician->title->name }}</td>
                <td>{{ $technician->shift->name ?? '' }}</td>
                <td>{{ $completedOrdersCount }}</td>
                <td>{{ $requiredOrdersTarget }}</td>
                <td>{{ $ordersShortage }}</td>
                <td>{{ $servicesAfterDiscount }}
                </td>
                <td>{{ $requiredServicesAmountTarget }}</td>
                <td>{{ $servicesAmountShortage }}</td>
                <td>{{ $donePercentage }}</td>
                <td>{{ $shortagePercentage }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
