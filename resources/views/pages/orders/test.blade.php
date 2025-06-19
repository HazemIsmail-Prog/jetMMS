<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.orders') }}
                <span id="counter"></span>
            </h2>
            <span id="excel"></span>
        </div>
    </x-slot>


    <div x-data="ordersComponent">

        <template x-teleport="#counter">
            <span 
                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                x-text="totalRecords"
            >
            </span>
        </template>

        <template x-teleport="#excel">
            <div>
                <template x-if="orders.length <= maxExportSize">
                    <x-button 
                        @click="handleExport"
                        wire:confirm="{{ __('messages.are_u_sure') }}"
                        wire:loading.class=" animate-pulse duration-75 cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        {{ __('messages.export_to_excel') }}
                    </x-button>
                </template>
            </div>
        </template>

        <!-- Filters -->
        <div class=" flex gap-3">
            <div class=" w-3/4">
                <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
            
                    <div>
                        <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
                        <x-input class="w-36 min-w-full text-center py-0" id="customer_name"
                            x-model="filters.customer_name" />
                    </div>
                    <div>
                        <x-label for="customer_phone">{{ __('messages.customer_phone') }}</x-label>
                        <x-input dir="ltr" type="number" class="w-36 min-w-full text-center py-0" id="customer_phone"
                            x-model="filters.customer_phone" />
                    </div>
                    <div>
                        <x-label for="area">{{ __('messages.area') }}</x-label>
                        <x-input class="w-36 min-w-full text-center py-0" id="area" x-model="filters.area" />
                    </div>
                    <div>
                        <x-label for="block">{{ __('messages.block') }}</x-label>
                        <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="block" x-model="filters.block" />
                    </div>
                    <div>
                        <x-label for="street">{{ __('messages.street') }}</x-label>
                        <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="street" x-model="filters.street" />
                    </div>
                </div>
                <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                    <div>
                        <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
                        <x-input id="order_number" x-model="filters.order_number" type="number" dir="ltr"
                            class="w-36 min-w-full text-center py-0" />
                    </div>
                    <div>
                        <x-label for="creator">{{ __('messages.creator') }}</x-label>
                        <x-input class="w-36 min-w-full text-center py-0" id="creator" x-model="filters.creator" />
                    </div>
                    <div>
                        <x-label for="status">{{ __('messages.status') }}</x-label>
                        <x-input class="w-36 min-w-full text-center py-0" id="status" x-model="filters.status" />
                    </div>
                    <div>
                        <x-label for="technician">{{ __('messages.technician') }}</x-label>
                        <x-input class="w-36 min-w-full text-center py-0" id="technician" x-model="filters.technician" />
                    </div>
                    <div>
                        <x-label for="department">{{ __('messages.department') }}</x-label>
                        <x-input class="w-36 min-w-full text-center py-0" id="department" x-model="filters.department" />
                    </div>
                    <div>
                        <x-label for="tags">{{ __('messages.orderTag') }}</x-label>
                        <x-input class="w-36 min-w-full text-center py-0" id="tags" x-model="filters.tags" />
                    </div>
            
                </div>
                <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-3 gap-3">
                    <div>
                        <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
                        <x-input id="start_created_at" class="w-36 min-w-full text-center py-0" type="date"
                            x-model="filters.start_created_at" />
                        <x-input id="end_created_at" class="w-36 min-w-full text-center py-0" type="date"
                            x-model="filters.end_created_at" />
                    </div>
                    <div>
                        <x-label for="start_completed_at">{{ __('messages.completed_at') }}</x-label>
                        <x-input id="start_completed_at" class="w-36 min-w-full text-center py-0" type="date"
                            x-model="filters.start_completed_at" />
                        <x-input id="end_completed_at" class="w-36 min-w-full text-center py-0" type="date"
                            x-model="filters.end_completed_at" />
                    </div>
                    <div>
                        <x-label for="start_cancelled_at">{{ __('messages.cancelled_at') }}</x-label>
                        <x-input id="start_cancelled_at" class="w-36 min-w-full text-center py-0" type="date"
                            x-model="filters.start_cancelled_at" />
                        <x-input id="end_cancelled_at" class="w-36 min-w-full text-center py-0" type="date"
                            x-model="filters.end_cancelled_at" />
                    </div>
            
                </div>
            </div>

            <!-- creator counters -->
            <div class=" border dark:border-gray-700 rounded-lg p-2  w-1/4 hidden-scrollbar h-56 overflow-y-auto">
                <div class="flex flex-col gap-2 items-end">
                    <template x-for="user in creatorCounters.sortByDesc('orders_creator_count')" :key="user.id">
                        <div title="user.name" class="w-full justify-start flex-row-reverse rounded-full overflow-clip bg-gray-200 dark:bg-gray-700 flex items-center">
                            <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-2 leading-none  truncate select-none"
                                :style="{width: (user.orders_creator_count / creatorCounters.max('orders_creator_count')) * 100 + '%'}" x-text="user.name">
                            </div>
                            <span class=" text-center px-2 font-semibold text-xs dark:text-white" x-text="user.orders_creator_count"></span>
                        </div>
                    </template>
                </div>
            </div>

        </div>



        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.order_number') }}</x-th>
                    <x-th>{{ __('messages.created_at') }}</x-th>
                    <x-th>{{ __('messages.estimated_start_date') }}</x-th>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.completed_date') }}</x-th>
                    <x-th>{{ __('messages.cancel_reason') }}</x-th>
                    <x-th>{{ __('messages.customer') }}</x-th>
                    <x-th>{{ __('messages.remaining_amount') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                <template x-for="order in orders" :key="order.id">
                    <x-tr>
                        <x-td x-text="order.formated_order_id"></x-td>

                        <x-td>
                            <div x-text="order.created_at.format('d-m-Y')"></div>
                            <div class=" text-[0.7rem]" x-text="order.created_at.format('H:i')"></div>
                            <div class="text-[0.7rem] w-20 whitespace-normal" x-text="order.creator.name"></div>
                        </x-td>

                        <x-td x-text="order.formated_estimated_start_date"></x-td>

                        <x-td x-text="order.status.name"></x-td>

                        <x-td class=" !whitespace-normal" x-text="order.department.name"></x-td>

                        <x-td class=" !whitespace-normal" x-text="order.technician.name ?? '-'"></x-td>

                        <x-td>
                            <div x-text="order.completed_at ? order.completed_at.format('d-m-Y') : (order.cancelled_at ? order.cancelled_at.format('d-m-Y') : '')"></div>
                            <div class="text-[0.7rem]" x-text="order.completed_at ? order.completed_at.format('H:i') : (order.cancelled_at ? order.cancelled_at.format('H:i') : '')"></div>
                        </x-td>
                        <x-td x-text="order.reason ?? '-'"></x-td>

                        <x-td>
                            <div class=" flex items-start gap-1">
                                <template x-if="order.can_edit_customer">
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        @click="$dispatch('showCustomerFormModal',{customer:order.customer})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                                <div class=" flex-1">
                                    <div x-text="order.customer.name"></div>
                                    <div x-text="order.customer.phone.number"></div>
                                    <div x-text="order.customer.address.full_address"></div>
                                    <template x-if="order.customer.notes">
                                    <div class=" text-red-400 font-normal !whitespace-normal" x-text="order.customer.notes"></div>
                                </div>

                            </div>
                        </x-td>
                        <x-td x-text="order.formated_remaining_amount"></x-td>
                        <x-td>
                            <div class=" flex items-center justify-end gap-2">

                                <template x-if="order.can_send_survey">
                                    <a class=" border dark:border-gray-700 rounded-lg p-1" target="__blank"
                                        :href="order.whatsapp_message">{{ __('messages.send_survey') }}</a>
                                </template>

                                <template x-if="order.can_edit">
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        @click="$dispatch('showOrderFormModal',{order:order,customer:order.customer})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="order.can_view_details">
                                    <x-badgeWithCounter title="{{ __('messages.order_details') }}"
                                        @click="$dispatch('showDetailsModal',{order:order})">
                                        <x-svgs.list class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="order.can_cancel">
                                    <x-badgeWithCounter @click="$dispatch('showCancelReasonModal',{order:order})">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="order.can_view_progress">
                                    <x-badgeWithCounter title="{{ __('messages.order_progress') }}"
                                        @click="$dispatch('showStatusesModal',{order:order})">
                                        <x-svgs.list class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="order.can_view_comments">
                                    <x-badgeWithCounter title="{{ __('messages.comments') }}"
                                        @click="$dispatch('showCommentsModal',{order:order})">
                                        <x-svgs.comment class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="order.can_view_invoices">
                                    <x-badgeWithCounter
                                        title="{{ __('messages.invoices') }}"
                                        @click="$dispatch('showInvoicesModal',{order:order})">
                                        <x-svgs.banknotes class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                            </div>
                        </x-td>
                    </x-tr>
                </template>
            </tbody>
        </x-table>


        <!-- load more letters -->
        <div class="flex justify-center mt-4" x-show="currentPage < lastPage">
            <x-button @click="loadMore">{{__('messages.load_more')}}</x-button>
        </div>
    </div>


    <script>
        function ordersComponent() {
            return {
                loading: false,
                customers: [],
                showModal: false,
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                filters: {
                    name: '',
                    phone: '',
                    area_id: [],
                    block: '',
                    street: '',
                    start_created_at: '',
                    end_created_at: '',
                },
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.getOrders();
                    this.$watch('filters', () => {
                        this.getOrders(1);
                    });
                    this.initListeners();
                },



                formatNumber(number) {
                    return number.toFixed(3);
                },


                getOrders(page=1) {
                    this.loading = true;
                    axios.get('/test/orders?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.orders = [];
                                this.orders = response.data.data;
                            } else {
                                this.orders = [...this.orders, ...response.data.data];
                            }
                            this.currentPage = response.data.meta.current_page;
                            this.lastPage = response.data.meta.last_page;
                            this.totalRecords = response.data.meta.total;
                        })

                        .catch(error => {
                            alert(error.response.data.message);
                        })                        
                        .finally(() => {
                            this.loading = false;
                        });
                },

                loadMore() {
                    if (this.currentPage == this.lastPage || this.loading) return;                    
                    this.currentPage = (this.currentPage || 1) + 1;
                    this.getOrders(this.currentPage);
                },

                updateOrder(e) {
                    const index = this.orders.findIndex(order => order.id === e.detail.order.id);
                    if(index !== -1) {
                        this.orders[index] = e.detail.order;
                    }
                },

                async getOrderResource(orderId) {
                    const response = await axios.get(`/test/getOrderResource/${orderId}`);
                    return response.data.data;
                },
                
                initListeners() {

                },

                checkFilters() {
                    return Object.values(this.filters).every(value => value === '');
                },


            }
        }
    </script>
</x-app-layout>
