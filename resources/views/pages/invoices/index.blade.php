<x-app-layout>
    <x-slot name="title">
        {{ __('messages.invoices') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.invoices') }}
                <span id="counter"></span>
            </h2>
            <span id="excel"></span>
        </div>
    </x-slot>

    <!-- single order modal -->
     @include('modals.single-order')

     <!-- invoice form modal -->
     @include('modals.invoice-form')

     <!-- discount form modal -->
     @include('modals.discount-form')

     <!-- payment form modal -->
     @include('modals.payment-form')


    <div
        x-data="invoicesComponent()"
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

        <!-- Filters -->
        <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3">
            <div>
                <x-label for="invoice_number">{{ __('messages.invoice_number') }}</x-label>
                <x-input type="number" id="invoice_number" x-model.debounce="filters.invoice_number" class="w-full text-start py-0" />
            </div>
            <div>
                <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
                <x-input type="number" id="order_number" x-model.debounce="filters.order_number" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="department">{{ __('messages.department') }}</x-label>
                <!-- start searchable select -->
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
                <!-- end searchable select -->
            </div>
            <div>
                <x-label for="technician">{{ __('messages.technician') }}</x-label>
                <!-- start searchable select -->
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
                <!-- end searchable select -->
            </div>

            <div>
                <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
                <x-input id="customer_name" x-model.debounce="filters.customer_name" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="customer_phone">{{ __('messages.customer_phone') }}</x-label>
                <x-input id="customer_phone" x-model.debounce="filters.customer_phone" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="payment_status">{{ __('messages.payment_status') }}</x-label>
                <x-select id="payment_status" x-model="filters.payment_status" class=" w-full py-0">
                    <option value="">---</option>
                    @foreach (App\Enums\PaymentStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->title() }}</option>
                    @endforeach
                </x-select>
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
                    <x-th>{{ __('messages.invoice_number') }}</x-th>
                    <x-th>{{ __('messages.order_number') }}</x-th>
                    <x-th>{{ __('messages.created_at') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.customer_name') }}</x-th>
                    <x-th>{{ __('messages.customer_phone') }}</x-th>
                    <x-th>{{ __('messages.services') }}</x-th>
                    <x-th>{{ __('messages.discount') }}</x-th>
                    <x-th>{{ __('messages.services_after_discount') }}</x-th>
                    <x-th>{{ __('messages.internal_parts') }}</x-th>
                    <x-th>{{ __('messages.external_parts') }}</x-th>
                    <x-th>{{ __('messages.delivery') }}</x-th>
                    <x-th>{{ __('messages.amount') }}</x-th>
                    <x-th>{{ __('messages.cash') }}</x-th>
                    <x-th>{{ __('messages.knet') }}</x-th>
                    <x-th>{{ __('messages.paid_amount') }}</x-th>
                    <x-th>{{ __('messages.remaining_amount') }}</x-th>
                    <x-th>{{ __('messages.payment_status') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="invoice in invoices" :key="invoice.id">
                    <x-tr x-bind:class="{
                        '!text-yellow-700': invoice.order.status_id !== 4,
                        '!text-red-500': invoice.deleted_at !== null
                    }">
                        <x-td x-text="invoice.formatted_id"></x-td>
                        <x-td>
                            <x-badgeWithCounter
                                title="{{ __('messages.view_order') }}"
                                @click="$dispatch('order-selected', {order: invoice.order})">
                                    <span x-text="invoice.formatted_order_id"></span>
                                    <span class="text-xs text-gray-500" x-show="invoice.order_invoices_count > 1" x-text="invoice.order_invoices_count"></span>
                            </x-badgeWithCounter>
                        </x-td>
                        <x-td dir="ltr" x-text="invoice.formatted_created_at"></x-td>
                        <x-td x-text="getDepartmentName(invoice.order.department_id)"></x-td>
                        <x-td x-text="getTechnicianName(invoice.order.technician_id)"></x-td>
                        <x-td x-text="invoice.order.customer.name"></x-td>
                        <x-td x-text="invoice.order.phone.number"></x-td>
                        <x-td x-text="formatNumber(invoice.invoice_details_services_amount)"></x-td>
                        <x-td x-text="formatNumber(invoice.discount)"></x-td>
                        <x-td x-text="formatNumber(invoice.invoice_details_services_amount - invoice.discount)"></x-td>
                        <x-td x-text="formatNumber(invoice.invoice_part_details_amount)"></x-td>
                        <x-td x-text="formatNumber(invoice.invoice_details_parts_amount)"></x-td>
                        <x-td x-text="formatNumber(invoice.delivery)"></x-td>
                        <x-td x-text="formatNumber(invoice.total_amount)"></x-td>
                        <x-td x-text="formatNumber(invoice.cash_payments_amount)"></x-td>
                        <x-td x-text="formatNumber(invoice.knet_payments_amount)"></x-td>
                        <x-td x-text="formatNumber(invoice.payments_amount)"></x-td>
                        <x-td x-text="formatNumber(invoice.remaining_balance)"></x-td>
                        <x-td x-text="invoice.payment_status"></x-td>
                        <x-td>
                            <div class="flex justify-end gap-2">
                                <template x-if="invoice.order_invoices_count > 1 && invoice.can_deleted && invoice.deleted_at === null">
                                    <x-badgeWithCounter
                                        class="px-2 border-red-500 dark:border-red-500 text-red-500 dark:text-red-500 hover:bg-red-500 hover:text-white"
                                        title="{{ __('messages.delete') }}"
                                        @click="deleteInvoice(invoice)">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                            </div>
                        </x-td>
                    </x-tr>
                </template>
            </tbody>
            <x-tfoot>
            <tr>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceDetailsServicesAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceDiscountAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceServicesAfterDiscountAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoicePartDetailsAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceDetailsPartsAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceDeliveryAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceTotalAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceCashAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceKnetAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoicePaymentsAmount)"></x-th>
                <x-th class="!text-right" x-text="formatNumber(totalInvoiceRemainingBalance)"></x-th>
                <x-th></x-th>
                <x-th></x-th>
            </tr>
        </x-tfoot>

        </x-table>

        <!-- load more letters -->
        <div class="flex justify-center mt-4" x-show="currentPage < lastPage">
            <x-button @click="loadMore">{{__('messages.load_more')}}</x-button>
        </div>
    </div>


    <script>
        function invoicesComponent() {
            return {
                departments: @js($departments),
                technicians: @js($technicians),
                loading: false,
                exporting: false,
                maxExportSize: 5000,
                invoices: [],
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                filters: {
                    invoice_number: '',
                    order_number: '',
                    department_ids: [],
                    technician_ids: [],
                    customer_name: '',
                    customer_phone: '',
                    payment_status: '',
                    start_created_at: null,
                    end_created_at: null,
                },

                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.$watch('filters', () => {
                        this.getInvoices(1);
                    });
                    this.initListeners();
                },

                get totalInvoiceDetailsServicesAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.invoice_details_services_amount, 0);
                },

                get totalInvoiceDetailsPartsAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.invoice_details_parts_amount, 0);
                },

                get totalInvoicePartDetailsAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.invoice_part_details_amount, 0);
                },

                get totalInvoiceDeliveryAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.delivery, 0);
                },

                get totalInvoiceDiscountAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.discount, 0);
                },

                get totalInvoiceServicesAfterDiscountAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + (invoice.invoice_details_services_amount - invoice.discount), 0);
                },

                get totalInvoiceCashAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.cash_payments_amount, 0);
                },

                get totalInvoiceKnetAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.knet_payments_amount, 0);
                },

                get totalInvoicePaymentsAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.payments_amount, 0);
                },

                get totalInvoiceRemainingBalance() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.remaining_balance, 0);
                },

                get totalInvoiceTotalAmount() {
                    return this.invoices.reduce((acc, invoice) => acc + invoice.total_amount, 0);
                },

                getDepartmentName(id) {
                    return this.departments.find(department => department.id === id)?.name;
                },

                getTechnicianName(id) {
                    return this.technicians.find(technician => technician.id === id)?.name;
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


                getInvoices(page=1) {
                    this.loading = true;
                    axios.get('/invoices?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.invoices = [];
                                this.invoices = response.data.data;
                            } else {
                                this.invoices = [...this.invoices, ...response.data.data];
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
                    this.getInvoices(this.currentPage);
                },

                initListeners() {

                },

                checkFilters() {
                    return Object.values(this.filters).every(value => value === '');
                },

                deleteInvoice(invoice) {
                    if(!confirm('{{ __('messages.delete_invoice_confirmation') }}')) {
                        return;
                    }
                    this.invoices = this.invoices.filter(i => i.id !== invoice.id);
                    this.totalRecords--;

                    axios.delete(`/orders/${invoice.order_id}/invoices/${invoice.id}`)
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            this.getInvoices(1);
                        });
                },

                exportToExcel() {
                    if(this.totalRecords > this.maxExportSize) {
                        alert('You can only export up to 5000 records');
                        return;
                    }
                    this.exporting = true;
                    axios.get('/invoices/exportToExcel', {
                        params: this.filters,
                        responseType: 'blob'
                    })
                    .then(response => {
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'Invoices.xlsx');
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
