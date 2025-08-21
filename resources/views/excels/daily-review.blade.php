<table>
    <thead>
        <tr>
            <th>{{ __('messages.technician') }}</th>
            <th>{{ __('messages.invoices') }} - {{ __('messages.amount') }}</th>
            <th>{{ __('messages.invoices') }} - {{ __('messages.parts_difference') }}</th>
            <th>{{ __('messages.cost_centers') }} - {{ __('messages.services') }}</th>
            <th>{{ __('messages.cost_centers') }} - {{ __('messages.parts') }}</th>
            <th>{{ __('messages.cost_centers') }} - {{ __('messages.delivery') }}</th>
            <th>{{ __('messages.accounts') }} - {{ __('messages.income_account_id') }}</th>
            <th>{{ __('messages.accounts') }} - {{ __('messages.cost_account_id') }}</th>
            <th>{{ __('messages.department') }}</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($data['departments'] as $department)
            @foreach ($data['titles'] as $title)
                @php
                    $invoices_total = 0;
                    $part_difference = 0;
                    $services = 0;
                    $parts = 0;
                    $delivery = 0;
                    $income = 0;
                    $cost = 0;
                @endphp
                @foreach ($data['technicians_data']->where('title_id', $title->id)->where('department_id', $department->id)->where('visible', true) as $technician)
                    <tr>
                        <td>{{ $technician['name'] }}</td>
                        <td>{{ $technician['invoices_total'] > 0 ? $technician['invoices_total'] : '' }}</td>
                        <td>{{ $technician['part_difference'] != 0 ? $technician['part_difference'] : '' }}</td>
                        <td>{{ $technician['services'] > 0 ? $technician['services'] : '' }}</td>
                        <td>{{ $technician['parts'] > 0 ? $technician['parts'] : '' }}</td>
                        <td>{{ $technician['delivery'] > 0 ? $technician['delivery'] : '' }}</td>
                        <td>{{ $technician['income'] > 0 ? $technician['income'] : '' }}</td>
                        <td>{{ $technician['cost'] > 0 ? $technician['cost'] : '' }}</td>
                        <td>{{ $technician['department'] }}</td>
                    </tr>
                    @php
                        $invoices_total += $technician['invoices_total'] > 0 ? $technician['invoices_total'] : 0;
                        $part_difference += $technician['part_difference'] != 0 ? $technician['part_difference'] : 0;
                        $services += $technician['services'] > 0 ? $technician['services'] : 0;
                        $parts += $technician['parts'] > 0 ? $technician['parts'] : 0;
                        $delivery += $technician['delivery'] > 0 ? $technician['delivery'] : 0;
                        $income += $technician['income'] > 0 ? $technician['income'] : 0;
                        $cost += $technician['cost'] > 0 ? $technician['cost'] : 0;
                    @endphp
                @endforeach
                @if ($data['technicians_data']->where('title_id', $title->id)->where('department_id', $department->id)->where('visible', true)->count() > 0)
                    <tr>
                        <td style="background-color: #f0f0f0;">{{ $title->name }}</td>
                        <td style="background-color: #f0f0f0;">{{ $invoices_total }}</td>
                        <td style="background-color: #f0f0f0;">{{ $part_difference }}</td>
                        <td style="background-color: #f0f0f0;">{{ $services }}</td>
                        <td style="background-color: #f0f0f0;">{{ $parts }}</td>
                        <td style="background-color: #f0f0f0;">{{ $delivery }}</td>
                        <td style="background-color: #f0f0f0;">{{ $income }}</td>
                        <td style="background-color: #f0f0f0;">{{ $cost }}</td>
                        <td style="background-color: #f0f0f0;"></td>
                    </tr>
                @endif
            @endforeach
        @endforeach

    </tbody>
</table>
