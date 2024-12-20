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
        @foreach ($data as $technician)
            <tr>

                @php
                    $amount = round($technician->grandTotal, 3);
                    $parts_difference = round($technician->PartDifferenceDebit - $technician->PartDifferenceCredit,3);
                    $income = abs($technician->incomeAccountDebit - $technician->incomeAccountCredit);
                    $cost = abs($technician->costAccountDebit - $technician->costAccountCredit);
                    $services = abs($technician->servicesCostCenterDebit - $technician->servicesCostCenterCredit);
                    $parts = abs($technician->partsCostCenterDebit - $technician->partsCostCenterCredit);
                    $delivery = abs($technician->deliveryCostCenterDebit - $technician->deliveryCostCenterCredit);
                @endphp

                <td>{{ $technician->name }}</td>
                <td>{{ $technician->title->name }}</td>
                <td>{{ $technician->department->name }}</td>
                <td>{{ $amount > 0 ? $amount : '' }}</td>
                <td>{{ $parts_difference == 0 ? '' : $parts_difference }}</td>
                <td>{{ $services > 0 ? $services : '' }}</td>
                <td>{{ $parts > 0 ? $parts : '' }}</td>
                <td>{{ $delivery > 0 ? $delivery : '' }}</td>
                <td>{{ $income > 0 ? $income : '' }}</td>
                <td>{{ $cost > 0 ? $cost : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
