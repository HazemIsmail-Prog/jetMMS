    <table>
        <thead>
            <tr>
                <th>{{ __('messages.invoice_number') }}</th>
                <th>{{ __('messages.manual_id') }}</th>
                <th>{{ __('messages.date') }}</th>
                <th>{{ __('messages.supplier') }}</th>
                <th>{{ __('messages.contact') }}</th>
                <th>{{ __('messages.amount') }}</th>
                <th>{{ __('messages.discount') }}</th>
                <th>{{ __('messages.cost_amount') }}</th>
                <th>{{ __('messages.sales_amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $part_invoice)
                <tr>
                    <td>{{ $part_invoice->id }}</td>
                    <td>{{ $part_invoice->manual_id }}</td>
                    <td>{!! $part_invoice->formated_date !!}</td>
                    <td>{{ $part_invoice->supplier->name }}</td>
                    <td>{{ $part_invoice->contact->name }}</td>
                    <td>{{ $part_invoice->formated_invoice_amount }}</td>
                    <td>{{ $part_invoice->formated_discount_amount }}</td>
                    <td>{{ $part_invoice->formated_cost_amount }}</td>
                    <td>{{ $part_invoice->formated_sales_amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
