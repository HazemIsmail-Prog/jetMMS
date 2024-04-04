<table>
    <thead>
        <tr>
            <th>{{ __('messages.invoice_number') }}</th>
            <th>{{ __('messages.order_number') }}</th>
            <th>{{ __('messages.created_at') }}</th>
            <th>{{ __('messages.department') }}</th>
            <th>{{ __('messages.technician') }}</th>
            <th>{{ __('messages.customer_name') }}</th>
            <th>{{ __('messages.customer_phone') }}</th>
            <th>{{ __('messages.services') }}</th>
            <th>{{ __('messages.discount') }}</th>
            <th>{{ __('messages.services_after_discount') }}</th>
            <th>{{ __('messages.internal_parts') }}</th>
            <th>{{ __('messages.external_parts') }}</th>
            <th>{{ __('messages.delivery') }}</th>
            <th>{{ __('messages.amount') }}</th>
            <th>{{ __('messages.cash') }}</th>
            <th>{{ __('messages.knet') }}</th>
            <th>{{ __('messages.paid_amount') }}</th>
            <th>{{ __('messages.remaining_amount') }}</th>
            <th>{{ __('messages.payment_status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td>{{ $row->formated_id }}</td>
                <td>{{ $row->order->formated_id }}</td>
                <td>{{ $row->created_at->format('Y/m/d') }}</td>
                <td>{{ $row->order->department->name }}</td>
                <td>{{ $row->order->technician->name }}</td>
                <td>{{ $row->order->customer->name }}</td>
                <td>{{ $row->order->phone->number }}</td>
                <td>{{ $row->services_amount != 0 ? $row->services_amount : '' }}</td>
                <td>{{ $row->discount_amount != 0 ? $row->discount_amount : '' }}</td>
                <td>{{ $row->service_amount_after_discount != 0 ? $row->service_amount_after_discount : '' }}</td>
                <td>{{ $row->internal_parts_amount != 0 ? $row->internal_parts_amount : '' }}</td>
                <td>{{ $row->external_parts_amount != 0 ? $row->external_parts_amount : '' }}</td>
                <td>{{ $row->delivery_amount != 0 ? $row->delivery_amount : '' }}</td>
                <td>{{ $row->amount != 0 ? $row->amount : '' }}</td>
                <td>{{ $row->cash_amount != 0 ? $row->cash_amount : '' }}</td>
                <td>{{ $row->knet_amount != 0 ? $row->knet_amount : '' }}</td>
                <td>{{ $row->total_paid_amount != 0 ? $row->total_paid_amount : '' }}</td>
                <td>{{ $row->remaining_amount != 0 ? $row->remaining_amount : '' }}</td>
                <td>{{ $row->payment_status->title() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
