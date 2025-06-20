<div 
    x-data="inProgressOrdersForCustomer"
    x-on:open-in-progress-orders-for-customer-modal.window="openModal"
>
    <!-- Modal -->
    <div x-on:close.stop="hideModal" x-on:keydown.escape.window="dismissible ? hideModal() : null" x-show="showModal"
        class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
        style="display: none;">
        <div x-show="showModal" class="fixed inset-0 transform transition-all" x-on:click="dismissible ? hideModal() : null"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>
    
        <div x-show="showModal"
            class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-7xl sm:mx-auto"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
    
            <!-- Close Button -->
            <button x-on:click="hideModal" type="button" class="
                absolute
                -top-2
                -start-2
                inline-flex
                items-center
                p-1
                rounded-full
                bg-gray-800
                dark:bg-gray-200
                text-white
                dark:text-gray-800
                focus:outline-none
                transition
                ease-in-out
                duration-150
            ">
                <x-svgs.close class="w-5 h-5" />
            </button>
    
            <template x-if="selectedCustomer">
                <div class="p-4"> 
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" x-text="'{{ __('messages.in_progress_orders_for_customer') }} ' + selectedCustomer.name"></h2>

                    <template x-if="loading">
                        <div class="flex justify-center items-center h-full">
                            <span class="spinner ms-2"></span>
                        </div>
                    </template>

                    <template x-if="orders.length > 0 && !loading">

                        <x-table>
                            <x-thead>
                                <tr>
                                    <x-th>{{ __('messages.order_number') }}</x-th>
                                    <x-th>{{ __('messages.created_at') }}</x-th>
                                    <x-th>{{ __('messages.department') }}</x-th>
                                    <x-th>{{ __('messages.status') }}</x-th>
                                    <x-th>{{ __('messages.technician') }}</x-th>
                                    <x-th>{{ __('messages.remaining_amount') }}</x-th>
                                    <x-th></x-th>
                                </tr>
                            </x-thead>
                            <tbody>
                                <template x-for="order in orders" :key="order.id">
                                    <x-tr>
                                        <x-td x-text="order.formatted_id"></x-td>

                                        <x-td>
                                            <div x-text="order.formatted_creation_date"></div>
                                            <div class=" text-[0.7rem]" x-text="order.formatted_creation_time"></div>
                                            <div class="text-[0.7rem] w-20 whitespace-normal" x-text="order.creator.name"></div>
                                        </x-td>

                                        <x-td x-text="order.department.name"></x-td>

                                        <x-td x-bind:style="`color: ${order.status.color};`" x-text="order.status.name"></x-td>

                                        <x-td x-text="order.technician?.name || '-'"></x-td>
                                        <x-td x-text="formatNumber(getRemainingBalance(order))"></x-td>
                                        <x-td>
                                            <div class="flex items-center justify-end gap-1">
                                                <template x-if="order.can_edit_order">
                                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                                        class="hover:bg-gray-100 dark:hover:bg-gray-700"
                                                        @click="$dispatch('open-order-form-modal',{order:order,customer:order.customer})">
                                                        <x-svgs.edit class="h-4 w-4" />
                                                    </x-badgeWithCounter>
                                                </template>
                                                <x-badgeWithCounter @click="openOrderModal(order)">
                                                    <x-svgs.list class="h-4 w-4" />
                                                </x-badgeWithCounter>
                                            </div>
                                        </x-td>
                                    </x-tr>
                                </template>
                            </tbody>
                        </x-table>

                    </template>

                </div>
            </template>
        </div>
    </div>

    <script>
        function inProgressOrdersForCustomer() {
            return {
                dismissible: true,
                selectedCustomer: null,
                showModal: false,
                orders: [],
                loading: false,


                openModal(e) {
                    this.selectedCustomer = e.detail.customer;
                    this.getOrders();
                    this.showModal = true;
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedCustomer = null;
                    this.orders = [];
                },

                getRemainingBalance(order) {
                    return order.invoices.reduce((acc, invoice) => acc + invoice.remaining_balance, 0);
                },

                formatNumber(number) {
                    if(number > 0) {
                        return number.toLocaleString('en-US', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
                    }
                    return '-';
                },

                getOrders() {
                    this.loading = true;
                    axios.get(`/customers/${this.selectedCustomer.id}/getInProgressOrders`)
                        .then(response => {
                            this.orders = response.data.data;
                            this.loading = false;
                        });
                },

                openOrderModal(order) {
                    this.$dispatch('order-selected', { order: order });
                },
            }
        }
    </script>
</div>