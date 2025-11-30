<x-app-layout>
    <x-slot name="title">
        {{ __('messages.customers') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.customers') }}
                <span id="counter"></span>
            </h2>
            <div id="addNew"></div>
        </div>
    </x-slot>

    @include('modals.customer-form')
    @include('modals.customer-contract-form')
    @include('modals.in-progress-orders-for-customer')
    @include('modals.all-orders-for-customer')
    @include('modals.single-order')
    @include('modals.invoice-form')
    @include('modals.discount-form')
    @include('modals.payment-form')
    @include('modals.order-form')

    @include('modals.attachments')
    @include('modals.attachment-form')

    <div
        x-data="customersComponent()"
        x-on:customer-created.window="addNewCustomer"
        x-on:customer-updated.window="updateCustomer"
        x-on:customer-contract-created.window="updateCustomerContracts"
        x-on:order-created.window="handleOrderCreated"
    >
        @can('create', App\Models\Customer::class)
            <template x-teleport="#addNew">
                <x-button @click="$dispatch('open-customer-form-modal')">
                    {{__('messages.add_customer')}}
                </x-button>
            </template>
        @endcan

        <template x-teleport="#counter">
            <span 
                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                x-text="totalRecords"
            >
            </span>
        </template>

        <!-- Filters -->
        <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
            <div>
                <x-label for="name">{{ __('messages.name') }}</x-label>
                <x-input id="name" x-model.debounce="filters.name" class="w-full text-start py-0" />
            </div>
            <div>
                <x-label for="phone">{{ __('messages.phone') }}</x-label>
                <x-input type="number" id="phone" x-model.debounce="filters.phone" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="area">{{ __('messages.area') }}</x-label>
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
                <x-label for="block">{{ __('messages.block') }}</x-label>
                <x-input id="block" x-model="filters.block" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="street">{{ __('messages.street') }}</x-label>
                <x-input id="street" x-model="filters.street" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="building">{{ __('messages.building') }}</x-label>
                <x-input id="building" x-model="filters.building" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
                <x-input id="start_created_at" class="w-36 min-w-full text-center py-0" type="date"
                    x-model="filters.start_created_at" />
                <x-input id="end_created_at" class="w-36 min-w-full text-center py-0" type="date"
                    x-model="filters.end_created_at" />
            </div>
        </div>

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{__('messages.name')}}</x-th>
                    <x-th>{{__('messages.phone')}}</x-th>
                    <x-th>{{__('messages.address')}}</x-th>
                    <x-th>{{__('messages.created_at')}}</x-th>
                    <x-th>{{__('messages.remaining_amount')}}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="customer in customers" :key="customer.id">
                    <x-tr>
                        <x-th>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center justify-between gap-2">
                                    <span x-text="customer.name"></span>
                                    <template x-if="customer.contracts_count > 0">
                                        <span
                                            class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-700 dark:text-green-300">
                                            {{ __('messages.contract') }}
                                        </span>
                                    </template>
                                </div>
                                <div class="text-red-400 font-normal text-xs" x-text="customer.notes"></div>
                            </div>
                        </x-th>
                        <x-td>
                            <template x-for="phone in customer.phones" :key="phone.id">
                                <div x-text="phone.number"></div>
                            </template>
                        </x-td>
                        <x-td>
                            <template x-for="address in customer.addresses" :key="address.id">
                                <div x-text="address.full_address"></div>
                            </template>
                        </x-td>
                        <x-td x-text="customer.formatted_created_at"></x-td>
                        <x-td x-text="formatNumber(getRemainingBalance(customer))"></x-td>
                        <x-td>
                            <div class="flex justify-end gap-2">
                                <template x-if="customer.in_progress_orders_count > 0">
                                    <x-badgeWithCounter class="px-2" title="{{ __('messages.in_progress_orders') }}" @click="$dispatch('open-in-progress-orders-for-customer-modal', {customer: customer})">
                                        <div class="text-xs w-4 text-center" x-text="customer.in_progress_orders_count"></div>
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="customer.total_orders_count > 0">
                                    <x-badgeWithCounter class="px-2" title="{{ __('messages.orders') }}" @click="$dispatch('open-all-orders-for-customer-modal', {customer: customer})">
                                        <div class="text-xs w-4 text-center" x-text="customer.total_orders_count"></div>
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="customer.can_create_orders">
                                    <x-badgeWithCounter 
                                        class="px-2"
                                        title="{{ __('messages.add_order') }}"
                                        @click="$dispatch('open-order-form-modal', {customer: customer})">
                                        <x-svgs.plus class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="customer.can_create_contracts">
                                    <x-badgeWithCounter 
                                        class="px-2"
                                        title="{{ __('messages.add_contract') }}"
                                        @click="$dispatch('open-customer-contract-form-modal', {customer: customer})"
                                    >
                                        {{ __('messages.add_contract') }}
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="customer.can_edit">
                                    <x-badgeWithCounter class="px-2" title="{{ __('messages.edit') }}"
                                        @click="$dispatch('open-customer-form-modal', {customer: customer})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="customer.can_delete">
                                    <x-badgeWithCounter
                                        class="px-2 border-red-500 dark:border-red-500 text-red-500 dark:text-red-500 hover:bg-red-500 hover:text-white"
                                        title="{{ __('messages.delete') }}"
                                        @click="deleteCustomer(customer.id)">
                                        <x-svgs.trash class="h-4 w-4" />
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
        function customersComponent() {
            return {
                areas: @js($globalAreasResource),
                loading: false,
                customers: [],
                showModal: false,
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                filters: {
                    name: '',
                    phone: '',
                    area_ids: [],
                    block: '',
                    street: '',
                    building: '',
                    start_created_at: '',
                    end_created_at: '',
                },

                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.$watch('filters', () => {
                        this.getCustomers(1);
                    });
                    this.initListeners();
                },

                formatNumber(number) {
                    if(number > 0) {
                        return number.toLocaleString('en-US', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
                    }
                    return '-';
                },

                getRemainingBalance(customer) {
                    return customer.orders.reduce((acc, order) => acc + order.invoices.reduce((acc, invoice) => acc + invoice.remaining_balance, 0), 0);
                },


                getCustomers(page=1) {
                    this.loading = true;
                    axios.get('/customers?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.customers = [];
                                this.customers = response.data.data;
                            } else {
                                this.customers = [...this.customers, ...response.data.data];
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
                    this.getCustomers(this.currentPage);
                },

                addNewCustomer(e) {
                    this.customers.unshift(e.detail.customer);
                },

                updateCustomer(e) {
                    const index = this.customers.findIndex(customer => customer.id === e.detail.customer.id);
                    if(index !== -1) {
                        this.customers[index] = e.detail.customer;
                    }
                },

                updateCustomerContracts(e) {
                    const index = this.customers.findIndex(customer => customer.id === e.detail.contract.customer_id);
                    if(index !== -1) {
                        this.customers[index].contracts_count++;
                    }
                },

                async getCustomerResource(customerId) {
                    const response = await axios.get(`/customers/${customerId}`);
                    return response.data.data;
                },
                
                initListeners() {
                    var channel = Echo.channel('customers');
                    channel.listen('CustomerCreatedEvent', async (data) => {
                        const customerResource = await this.getCustomerResource(data.customer.id);
                        if(this.checkFilters()) {
                            this.customers.unshift(customerResource);
                        }
                    });
                    channel.listen('CustomerDeletedEvent', (data) => {
                        const index = this.customers.findIndex(customer => customer.id === data.customer.id);
                        if (index !== -1) {
                            this.customers.splice(index, 1);
                            this.totalRecords--;
                        }
                    });
                    channel.listen('CustomerUpdatedEvent', async (data) => {
                        const customerResource = await this.getCustomerResource(data.customer.id);
                        const index = this.customers.findIndex(customer => customer.id === data.customer.id);
                        if(index !== -1) {
                            this.customers[index] = customerResource;
                        }
                    });
                    channel.listen('OrderCreatedEvent', (data) => {
                        const index = this.customers.findIndex(customer => customer.id === data.order.customer_id);
                        if(index !== -1) {
                            this.customers[index].total_orders_count++;
                            this.customers[index].in_progress_orders_count++;
                        }
                    });
                },

                checkFilters() {
                    return Object.values(this.filters).every(value => value === '');
                },

                deleteCustomer(id) {
                    if(!confirm('{{ __('messages.delete_customer_confirmation') }}')) {
                        return;
                    }
                    this.customers = this.customers.filter(customer => customer.id !== id);
                    this.totalRecords--;

                    axios.delete(`/customers/${id}`)
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            this.getCustomers(1);
                        });
                },

                handleOrderCreated(e) {
                    const index = this.customers.findIndex(customer => customer.id === e.detail.order.customer_id);
                    if(index !== -1) {
                        this.customers[index].total_orders_count++;
                        this.customers[index].in_progress_orders_count++;
                    }
                },
            }
        }
    </script>
</x-app-layout>
