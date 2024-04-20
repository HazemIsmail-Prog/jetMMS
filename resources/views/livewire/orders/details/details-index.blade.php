<div>
    @if ($order)
        <x-table>
                <x-tr>
                    <x-th>{{ __('messages.order_number') }}</x-th>
                    <x-td>{{$order->formated_id}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.customer_name') }}</x-th>
                    <x-td class=" !whitespace-normal">{{$order->customer->name}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.phone') }}</x-th>
                    <x-td>{{$order->phone->number}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.address') }}</x-th>
                    <x-td class=" !whitespace-normal">{{$order->address->full_address}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.service_type') }}</x-th>
                    <x-td class=" !whitespace-normal">{{$order->department->name}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.estimated_start_date') }}</x-th>
                    <x-td>{!!$order->formated_estimated_start_date!!}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-td style="color: {{$order->status->color}}">{{$order->status->name}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-td class=" !whitespace-normal">{{$order->technician->name ?? '-'}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.order_description') }}</x-th>
                    <x-td class=" !whitespace-normal">{{$order->order_description ?? '-'}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-td class=" !whitespace-normal">{{$order->notes ?? '-'}}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.created_at') }}</x-th>
                    <x-td>{!!$order->formated_created_at!!}</x-td>
                </x-tr>
                <x-tr>
                    <x-th>{{ __('messages.creator') }}</x-th>
                    <x-td class=" !whitespace-normal">{{$order->creator->name}}</x-td>
                </x-tr>

        </x-table>
    @endif
</div>
