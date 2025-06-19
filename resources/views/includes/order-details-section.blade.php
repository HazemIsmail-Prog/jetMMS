<div class=" border dark:border-gray-700 rounded-lg p-3">
    <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('messages.order_details') }}
    </div>
    <x-section-border />
    <x-table>
        <x-tr>
            <x-th>{{ __('messages.order_number') }}</x-th>
            <x-td x-text="selectedOrder.formatted_id"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.customer_name') }}</x-th>
            <x-td class=" !whitespace-normal" x-text="selectedOrder.customer.name"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.phone') }}</x-th>
            <x-td x-text="selectedOrder.phone.number"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.address') }}</x-th>
            <x-td class=" !whitespace-normal" x-text="selectedOrder.address.full_address"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.service_type') }}</x-th>
            <x-td class=" !whitespace-normal" x-text="selectedOrder.department.name"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.estimated_start_date') }}</x-th>
            <x-td x-text="selectedOrder.formatted_estimated_start_date"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.status') }}</x-th>
            <x-td x-bind:style="'color: ' + getStatusColorById(selectedOrder.status_id)" x-text="getStatusNameById(selectedOrder.status_id)"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.technician') }}</x-th>
            <x-td class=" !whitespace-normal" x-text="selectedOrder.technician_id ? getTechnicianNameById(selectedOrder.technician_id) : '-'"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.order_description') }}</x-th>
            <x-td class=" !whitespace-normal" x-text="selectedOrder.order_description ?? '-'"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.notes') }}</x-th>
            <x-td class=" !whitespace-normal" x-text="selectedOrder.notes ?? '-'"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.created_at') }}</x-th>
            <x-td x-text="selectedOrder.formatted_created_at"></x-td>
        </x-tr>
        <x-tr>
            <x-th>{{ __('messages.creator') }}</x-th>
            <x-td class=" !whitespace-normal" x-text="selectedOrder.creator ? selectedOrder.creator.name : '-'"></x-td>
        </x-tr>

    </x-table>
</div>