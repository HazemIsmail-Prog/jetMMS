<div 
    x-data="orderStatusesComponent"
    x-on:order-updated.window="getOrderStatuses"
>
    <div class=" border dark:border-gray-700 rounded-lg p-3">
        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('messages.order_progress') }}
        </div>
        <x-section-border />
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th>{{ __('messages.reason') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.date') }}/{{ __('messages.time') }}</x-th>
                    <x-th>{{ __('messages.creator') }}</x-th>
                </tr>
            </x-thead>
            <tbody>
                <template x-for="status in orderStatuses" :key="status.id">
                    <x-tr>
                        <x-td x-bind:style="`color: ${getStatusColorById(status.status_id)}`" x-text="getStatusNameById(status.status_id)"></x-td>
                        <x-td class=" !whitespace-normal" x-text="status.reason ?? '-'"></x-td>
                        <x-td class=" !whitespace-normal" x-text="status.technician ? status.technician.name : '-'"></x-td>
                        <x-td>
                            <div x-text="status.date"></div>
                            <div class=" text-xs" x-text="status.time"></div>
                        </x-td>
                        <x-td class=" !whitespace-normal" x-text="status.creator ? status.creator.name : '-'"></x-td>
                    </x-tr>
                </template>
            </tbody>
        </x-table>
    </div>
    <script>
        function orderStatusesComponent() {
            return {
                orderStatuses: [],
                init() {
                    this.getOrderStatuses();
                },
                getOrderStatuses() {
                    if(!this.selectedOrder) return;
                    axios.get('/orders/' + this.selectedOrder.id + '/getOrderStatuses')
                        .then(response => {
                            this.orderStatuses = response.data.data;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            }
        }
    </script>
</div>