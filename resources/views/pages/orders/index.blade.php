<x-app-layout>
    <x-slot name="title">
        {{ __('messages.orders') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.orders') }}
                <span id="counter"></span>
            </h2>
            <span id="excel"></span>
        </div>
    </x-slot>

    @include('modals.single-order')
    @include('modals.customer-form')
    @include('modals.order-form')
    @include('modals.invoice-form')
    @include('modals.discount-form')
    @include('modals.payment-form')


    <div 
        x-data="ordersComponent"
        @order-updated.window="handleOrderUpdatedEvent"
        @order-created.window="handleOrderCreatedEvent"
    >

        <template x-teleport="#counter">
            <span 
                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                x-text="totalRecords"
            >
            </span>
        </template>

        <template x-teleport="#excel">
            <x-button 
                @click="exportToExcel"
                x-bind:disabled="exporting || totalRecords > maxExportSize"
                x-bind:class="{
                    'animate-pulse duration-75 cursor-not-allowed': exporting,
                    'cursor-not-allowed': totalRecords > maxExportSize
                }"
            >
                {{ __('messages.export_to_excel') }}
            </x-button>
        </template>

        <!-- Filters Toggle Button -->
        <div class="mb-4">
            <button 
                @click="showFilters = !showFilters" 
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
            >
                <span x-text="showFilters ? '{{ __('messages.hide_filters') }}' : '{{ __('messages.show_filters') }}'"></span>
                <svg 
                    class="w-4 h-4 transition-transform duration-200" 
                    :class="{ 'rotate-180': showFilters }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- New Filters UI -->
        <div 
            x-cloak
            x-show="showFilters"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="bg-white dark:bg-gray-900 rounded-lg shadow-lg mb-6 border-2 border-indigo-100 dark:border-gray-700"
        >
            <div class="p-4 border-b-2 border-indigo-100 dark:border-gray-700 bg-indigo-50/50 dark:bg-gray-800 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-indigo-900 dark:text-white">{{ __('messages.filters') }}</h3>
                    <button @click="resetFilters" class="text-sm text-indigo-600 dark:text-gray-300 hover:text-indigo-900 dark:hover:text-white">
                        {{ __('messages.reset_filters') }}
                    </button>
                </div>
            </div>
            
            <div class="p-4 bg-gradient-to-b from-white to-indigo-50/30 dark:from-gray-900 dark:to-gray-800">
                <!-- Customer Information Section -->
                <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-indigo-100/50 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-indigo-700 dark:text-gray-200 mb-3">{{ __('messages.customer_information') }}</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4">
                        <div>
                            <x-label for="customer_name" class="text-sm">{{ __('messages.customer_name') }}</x-label>
                            <x-input class="w-full text-sm" id="customer_name" x-model="filters.customer_name" />
                        </div>
                        <div>
                            <x-label for="customer_phone" class="text-sm">{{ __('messages.customer_phone') }}</x-label>
                            <x-input dir="ltr" type="number" class="w-full text-sm" id="customer_phone" x-model="filters.customer_phone" />
                        </div>
                        <div>
                            <x-label for="area" class="text-sm">{{ __('messages.area') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:areas,
                                    selectedItemIds:filters.area_ids,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemIds"
                                x-modelable="filters.area_ids"
                            >
                                <x-multipule-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                        </div>
                        <div>
                            <x-label for="block" class="text-sm">{{ __('messages.block') }}</x-label>
                            <x-input dir="ltr" class="w-full text-sm" id="block" x-model="filters.block" />
                        </div>
                        <div>
                            <x-label for="street" class="text-sm">{{ __('messages.street') }}</x-label>
                            <x-input dir="ltr" class="w-full text-sm" id="street" x-model="filters.street" />
                        </div>
                        <div>
                            <x-label for="jadda" class="text-sm">{{ __('messages.jadda') }}</x-label>
                            <x-input dir="ltr" class="w-full text-sm" id="jadda" x-model="filters.jadda" />
                        </div>
                        <div>
                            <x-label for="building" class="text-sm">{{ __('messages.building') }}</x-label>
                            <x-input dir="ltr" class="w-full text-sm" id="building" x-model="filters.building" />
                        </div>
                    </div>
                </div>

                <!-- Order Information Section -->
                <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-indigo-100/50 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-indigo-700 dark:text-gray-200 mb-3">{{ __('messages.order_information') }}</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        <div>
                            <x-label for="order_number" class="text-sm">{{ __('messages.order_number') }}</x-label>
                            <x-input id="order_number" x-model="filters.order_number" type="number" dir="ltr" class="w-full text-sm" />
                        </div>
                        <div>
                            <x-label for="creator" class="text-sm">{{ __('messages.creator') }}</x-label>
                            <!-- start creator searchable select -->
                            <div 
                                x-data="{
                                    items:creators,
                                    selectedItemIds:filters.creator_ids,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemIds"
                                x-modelable="filters.creator_ids"
                            >
                                <x-multipule-searchable-select />
                            </div>
                            <!-- end creator searchable select -->
                        </div>
                        <div>
                            <x-label for="status" class="text-sm">{{ __('messages.status') }}</x-label>
                            <!-- start status searchable select -->
                            <div 
                                x-data="{
                                    items:statuses,
                                    selectedItemIds:filters.status_ids,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemIds"
                                x-modelable="filters.status_ids"
                            >
                                <x-multipule-searchable-select />
                            </div>
                            <!-- end status searchable select -->
                        </div>
                        <div>
                            <x-label for="technician" class="text-sm">{{ __('messages.technician') }}</x-label>
                            <!-- start technician searchable select -->
                            <div 
                                x-data="{
                                    items:technicians,
                                    selectedItemIds:filters.technician_ids,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemIds"
                                x-modelable="filters.technician_ids"
                            >
                                <x-multipule-searchable-select />
                            </div>
                            <!-- end technician searchable select -->
                        </div>
                        <div>
                            <x-label for="department" class="text-sm">{{ __('messages.department') }}</x-label>
                            <!-- start department searchable select -->
                            <div 
                                x-data="{
                                    items:departments,
                                    selectedItemIds:filters.department_ids,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemIds"
                                x-modelable="filters.department_ids"
                            >
                                <x-multipule-searchable-select />
                            </div>
                            <!-- end department searchable select -->
                        </div>
                        <div>
                            <x-label for="tags" class="text-sm">{{ __('messages.orderTag') }}</x-label>
                            <x-select class="w-full text-sm" id="tags" x-model="filters.tags">
                                <option value="">---</option>
                                <template x-for="tag in tags" :key="tag">
                                    <option :value="tag" x-text="tag"></option>
                                </template>
                            </x-select>
                        </div>
                    </div>
                </div>

                <!-- Date Filters Section -->
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-indigo-100/50 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-indigo-700 dark:text-gray-200 mb-3">{{ __('messages.date_filters') }}</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <x-label for="start_created_at" class="text-sm">{{ __('messages.created_at') }}</x-label>
                            <div class="flex gap-2">
                                <x-input id="start_created_at" class="w-full text-sm" type="date" x-model="filters.start_created_at" />
                                <x-input id="end_created_at" class="w-full text-sm" type="date" x-model="filters.end_created_at" />
                            </div>
                        </div>
                        <div>
                            <x-label for="start_completed_at" class="text-sm">{{ __('messages.completed_at') }}</x-label>
                            <div class="flex gap-2">
                                <x-input id="start_completed_at" class="w-full text-sm" type="date" x-model="filters.start_completed_at" />
                                <x-input id="end_completed_at" class="w-full text-sm" type="date" x-model="filters.end_completed_at" />
                            </div>
                        </div>
                        <div>
                            <x-label for="start_cancelled_at" class="text-sm">{{ __('messages.cancelled_at') }}</x-label>
                            <div class="flex gap-2">
                                <x-input id="start_cancelled_at" class="w-full text-sm" type="date" x-model="filters.start_cancelled_at" />
                                <x-input id="end_cancelled_at" class="w-full text-sm" type="date" x-model="filters.end_cancelled_at" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <template x-for="(order, index) in orders" :key="order.id">
                <div class="rounded-lg shadow p-3 transition-all duration-200 hover:shadow-lg hover:scale-[1.01] cursor-default border border-gray-200 dark:border-gray-600"
                     :class="index % 2 === 0 ? 'bg-indigo-50 dark:bg-indigo-900/50' : 'bg-white dark:bg-gray-800'">
                    <!-- Order Header -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900 dark:text-gray-100" x-text="order.formatted_id"></span>
                            <span class="px-2 py-0.5 pb-1 text-xs rounded-full flex items-center" 
                                :style="{ backgroundColor: getStatusColorById(order.status_id) + '20', color: getStatusColorById(order.status_id) }"
                                x-text="getStatusNameById(order.status_id)">
                            </span>
                        </div>
                        <template x-if="getInvoicesRemainingBalance(order) > 0">
                            <div class="text-right">
                                <div class="text-xs text-gray-600 dark:text-gray-400">{{ __('messages.remaining_amount') }}</div>
                                <div class="text-sm font-semibold text-red-600 dark:text-red-400" x-text="formatNumber(getInvoicesRemainingBalance(order))"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Order Content -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <!-- Left Column -->
                        <div class="flex gap-4">
                            <div>
                                <div class="text-gray-600 dark:text-gray-400">{{ __('messages.created_at') }}</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100" x-text="order.formatted_creation_date"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="order.formatted_creation_time"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="getCreatorNameById(order.created_by)"></div>
                            </div>
                            <div>
                                <div class="text-gray-600 dark:text-gray-400">{{ __('messages.estimated_start_date') }}</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100" x-text="order.formatted_estimated_start_date"></div>
                            </div>
                            <template x-if="order.status_id == 4"> <!-- Completed status -->
                                <div>
                                    <div class="text-gray-600 dark:text-gray-400">{{ __('messages.completed_date') }}</div>
                                    <div class="font-medium text-green-600 dark:text-green-400" x-text="order.formatted_completion_date"></div>
                                    <div class="text-xs text-green-600 dark:text-green-400" x-text="order.formatted_completion_time"></div>
                                </div>
                            </template>
                            <template x-if="order.status_id == 6"> <!-- Cancelled status -->
                                <div>
                                    <div class="text-gray-600 dark:text-gray-400">{{ __('messages.cancelled_at') }}</div>
                                    <div class="font-medium text-red-600 dark:text-red-400" x-text="order.formatted_cancellation_date"></div>
                                    <div class="text-xs text-red-600 dark:text-red-400" x-text="order.formatted_cancellation_time"></div>
                                    <template x-if="order.reason">
                                        <div class="mt-1">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.reason') }}:</div>
                                            <div class="text-xs text-red-600 dark:text-red-400 italic" x-text="order.reason"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Middle Column -->
                        <div class="flex gap-4 mt-4 md:mt-0">
                            <div>
                                <div class="text-gray-600 dark:text-gray-400">{{ __('messages.department') }}</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100" x-text="getDepartmentNameById(order.department_id)"></div>
                                <template x-if="order.technician_id">
                                    <div class="mt-1">
                                        <div class="text-gray-600 dark:text-gray-400">{{ __('messages.technician') }}</div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100" x-text="getTechnicianNameById(order.technician_id)"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="mt-4 md:mt-0">
                            <div class="flex items-center gap-2">
                                <template x-if="order.customer.can_edit">
                                    <x-badgeWithCounter @click="$dispatch('open-customer-form-modal',{customer:order.customer})" title="{{ __('messages.edit') }}" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100" x-text="order.customer.name"></div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400" x-text="order.phone.number"></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500" x-text="order.address.full_address"></div>
                                    <div x-show="order.customer.notes" class="text-xs text-red-400" x-text="order.customer.notes"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex flex-wrap items-center gap-1">
                            <template x-if="order.can_send_survey && order.status_id == 4">
                                <a class="inline-flex items-center px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   target="__blank" 
                                   :href="order.whatsapp_message">
                                    {{ __('messages.send_survey') }}
                                </a>
                            </template>

                            <template x-if="order.can_edit_order">
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700"
                                    @click="$dispatch('open-order-form-modal',{order:order,customer:order.customer})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            </template>

                            <template x-if="order.can_view_order_details">
                                <x-badgeWithCounter title="{{ __('messages.order_details') }}"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700"
                                    @click="openOrderModal(order)">
                                    <x-svgs.list class="h-4 w-4" />
                                </x-badgeWithCounter>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>


        <!-- load more letters -->
        <div class="flex justify-center mt-4" x-show="currentPage < lastPage">
            <x-button @click="loadMore">{{__('messages.load_more')}}</x-button>
        </div>

    </div>

    <script>
        function ordersComponent() {
            return {
                loading: false,
                exporting: false,
                maxExportSize: 5000,
                orders: [],
                areas: @js($globalAreasResource),
                statuses: @js($globalStatusesResource),
                departments: @js($departments),
                technicians: @js($technicians),
                creators: @js($creators),
                tags: @js($tags),
                showModal: false,
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                showFilters: false,
                filters: {
                    customer_name: '',
                    customer_phone: '',
                    area_ids: [],
                    block: '',
                    street: '',
                    jadda: '',
                    building: '',
                    order_number: '',
                    creator_ids: [],
                    status_ids: [],
                    technician_ids: [],
                    department_ids: [],
                    tags: '',
                    start_created_at: '',
                    end_created_at: '',
                    start_completed_at: '',
                    end_completed_at: '',
                    start_cancelled_at: '',
                    end_cancelled_at: '',
                },
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.$watch('filters', () => {
                        // this will run also when the page is loaded
                        this.getOrders(1);
                    });
                    this.initListeners();
                },

                formatNumber(number) {
                    if(number > 0) {
                        return number.toLocaleString('en-US', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
                    }
                    return '-';
                },

                getInvoicesRemainingBalance(order) {
                    return order.invoices.reduce((acc, invoice) => acc + invoice.remaining_balance, 0);
                },

                getDepartmentNameById(id) {
                    return this.departments.find(department => department.id === id)?.name;
                },

                getTechnicianNameById(id) {
                    return this.technicians.find(technician => technician.id === id)?.name;
                },

                getCreatorNameById(id) {
                    return this.creators.find(creator => creator.id === id)?.name;
                },

                getStatusNameById(id) {
                    return this.statuses.find(status => status.id === id)?.name;
                },

                getStatusColorById(id) {
                    return this.statuses.find(status => status.id === id)?.color;
                },

                openOrderModal(order) {
                    this.$dispatch('order-selected', {order: order});
                },


                getOrders(page=1) {
                    this.loading = true;
                    axios.get('/orders?page=' + page, {params: this.filters})
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

                handleOrderUpdatedEvent(e) {
                    const index = this.orders.findIndex(order => order.id === e.detail.order.id);
                    if(index !== -1) {
                        this.orders[index] = e.detail.order;
                    }
                },

                handleOrderCreatedEvent(e) {
                    this.orders.unshift(e.detail.order);
                },

                async getOrderResource(orderId) {
                    const response = await axios.get(`/orders/${orderId}`);
                    return response.data.data;
                },
                
                initListeners() {
                    var channel = Echo.channel('orders');
                    channel.listen('OrderCreatedEvent', async (data) => {
                        const orderResource = await this.getOrderResource(data.order.id);
                        if(this.checkFilters()) {
                            // this will prevent the order from being added to the orders array if there are filters applied
                            this.orders.unshift(orderResource);
                            this.totalRecords++;
                        }
                    });
                    channel.listen('OrderUpdatedEvent', async (data) => {
                        const index = this.orders.findIndex(order => order.id === data.order.id);
                        if (index !== -1) {
                            const orderResource = await this.getOrderResource(data.order.id);
                            this.orders[index] = orderResource;
                        }
                    });
                },

                checkFilters() {
                    // check if all filters string values are empty and array values are empty
                    return Object.values(this.filters).every(value => value === '') && Object.values(this.filters).every(value => value.length === 0);
                },

                resetFilters() {
                    this.filters = {
                        customer_name: '',
                        customer_phone: '',
                        area_ids: [],
                        block: '',
                        street: '',
                        jadda: '',
                        building: '',
                        order_number: '',
                        creator_ids: [],
                        status_ids: [],
                        technician_ids: [],
                        department_ids: [],
                        tags: '',
                        start_created_at: '',
                        end_created_at: '',
                        start_completed_at: '',
                        end_completed_at: '',
                        start_cancelled_at: '',
                        end_cancelled_at: '',
                    };
                    this.getOrders(1);
                },

                exportToExcel() {
                    if(this.totalRecords > this.maxExportSize) {
                        alert('You can only export up to 5000 records');
                        return;
                    }
                    this.exporting = true;
                    axios.get('/orders/exportToExcel', {
                        params: this.filters,
                        responseType: 'blob'
                    })
                    .then(response => {
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'Orders.xlsx');
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(error => {
                        alert('Error downloading file: ' + error.message);
                    })
                    .finally(() => {
                        this.exporting = false;
                    });
                },
            }
        }
    </script>


</x-app-layout>
