<table>
    <thead>
        <tr>
            <th>{{ __('messages.technician') }}</th>
            <th>{{ __('messages.title') }}</th>
            <th>{{ __('messages.department') }}</th>
            <th>{{ __('messages.invoices') }} - {{ __('messages.amount') }}</th>
            <th>{{ __('messages.invoices') }} - {{ __('messages.parts_difference') }}</th>
            <th>{{ __('messages.cost_centers') }} - {{ __('messages.services') }}</th>
            <th>{{ __('messages.cost_centers') }} - {{ __('messages.parts') }}</th>
            <th>{{ __('messages.cost_centers') }} - {{ __('messages.delivery') }}</th>
            <th>{{ __('messages.accounts') }} - {{ __('messages.income_account_id') }}</th>
            <th>{{ __('messages.accounts') }} - {{ __('messages.cost_account_id') }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data->sortBy('title_id')->sortBy('department_id') as $technician)
            @if ($technician['visible'])
                <tr>
                    <td>{{ $technician['name'] }}</td>
                    <td>{{ $technician['title'] }}</td>
                    <td>{{ $technician['department'] }}</td>
                    <td>{{ $technician['invoices_total'] > 0 ? $technician['invoices_total'] : '' }}</td>
                    <td>{{ $technician['part_difference'] != 0 ? $technician['part_difference'] : '' }}</td>
                    <td>{{ $technician['services'] > 0 ? $technician['services'] : '' }}</td>
                    <td>{{ $technician['parts'] > 0 ? $technician['parts'] : '' }}</td>
                    <td>{{ $technician['delivery'] > 0 ? $technician['delivery'] : '' }}</td>
                    <td>{{ $technician['income'] > 0 ? $technician['income'] : '' }}</td>
                    <td>{{ $technician['cost'] > 0 ? $technician['cost'] : '' }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
