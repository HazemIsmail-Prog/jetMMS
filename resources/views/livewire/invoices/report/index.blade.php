<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.invoices_per_technician_report') }}
        </h2>
    </x-slot>

    <div x-data="invoicesReport()">
        <div class=" flex items-center justify-between mb-3">

            <div class=" flex items-end gap-3 no-print">
                <div class=" col-span-1 md:col-span-2 xl:col-span-4">
                    <x-label for="start_date">{{ __('messages.date') }}</x-label>
                    <x-input type="date" class="w-full" id="start_date" x-model="start_date" />
                    <x-input type="date" class="w-full" id="end_date" x-model="end_date" />
                </div>
    
                <x-button type="button"
                x-on:click="fetchData">{{ __('messages.update') }}</x-button> 
            </div>

            <div x-show="isloading">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-10 h-10 animate-spin">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </div>
        </div>
        <template x-if="!isloading && technicians.length">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>{{ __('messages.name') }}</x-th>
                        <x-th>{{ __('messages.department') }}</x-th>
                        <x-th>{{ __('messages.orders') }}</x-th>
                        <x-th>{{ __('messages.invoices') }}</x-th>
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
                    </tr>
                </x-thead>
                <tbody>
                    <template x-for="technician in technicians" :key="technician.id">
                        <x-tr>
                            <x-td x-text="technician.name"></x-td>
                            <x-td x-text="technician.departmentName"></x-td>
                            <x-td x-text="technician.ordersCount == 0 ? '-' : technician.ordersCount"></x-td>
                            <x-td x-text="technician.invoicesCount == 0 ? '-' : technician.invoicesCount"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.servicesAmountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.discountSum)"></x-td>
                            <x-td class=" !text-right"
                                x-text="formatNumber(technician.servicesAfterDiscountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.internalPartsAmountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.externalPartsAmountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.deliverySum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.totalAmountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.cashAmountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.knetAmountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.paidAmountSum)"></x-td>
                            <x-td class=" !text-right" x-text="formatNumber(technician.remainingAmountSum)"></x-td>
                        </x-tr>
                    </template>
                </tbody>

            </x-table>
        </template>
    </div>
</x-app-layout>

<script>
    function invoicesReport() {
        return {
            isloading: false,
            depatments: [],
            technicians: [],
            orders: [],
            start_date: new Date().toISOString().split('T')[0], // today date
            end_date: new Date().toISOString().split('T')[0],
            numberFormatter: new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            }),

            init() {
                this.fetchData()
            },

            formatNumber(value) {
                return value === 0 ? '-' : this.numberFormatter.format(value);
            },

            aggregateData() {
                this.technicians = this.technicians.map(technician => {
                    const technicianOrders = this.orders.filter(order => order.technician_id === technician.id);
                    const technicianInvoices = this.invoices.filter(invoice => invoice.technician_id ===
                        technician.id);
                    const servicesAmountSum = technicianInvoices.reduce((sum, invoice) => sum + invoice
                        .servicesAmountSum, 0);
                    const discountSum = technicianInvoices.reduce((sum, invoice) => sum + invoice.discount, 0);
                    const internalPartsAmountSum = technicianInvoices.reduce((sum, invoice) => sum + (invoice
                        .invoiceDetailsPartsAmountSum + invoice.internalPartsAmountSum), 0);
                    const externalPartsAmountSum = technicianInvoices.reduce((sum, invoice) => sum + invoice
                        .externalPartsAmountSum, 0);
                    const deliverySum = technicianInvoices.reduce((sum, invoice) => sum + invoice.delivery, 0);
                    const cashAmountSum = technicianInvoices.reduce((sum, invoice) => sum + invoice
                        .totalCashAmountSum, 0);
                    const knetAmountSum = technicianInvoices.reduce((sum, invoice) => sum + invoice
                        .totalKnetAmountSum, 0);
                    const servicesAfterDiscountSum = servicesAmountSum - discountSum;
                    const totalAmountSum = servicesAfterDiscountSum + internalPartsAmountSum +
                        externalPartsAmountSum + deliverySum;
                    const paidAmountSum = cashAmountSum + knetAmountSum;
                    const remainingAmountSum = totalAmountSum - paidAmountSum;

                    return {
                        ...technician,
                        departmentName: this.departments.filter(department => department.id === technician
                            .department_id)[0].name,
                        ordersCount: technicianOrders.length,
                        invoicesCount: technicianInvoices.length,
                        servicesAmountSum,
                        discountSum,
                        servicesAfterDiscountSum,
                        internalPartsAmountSum,
                        externalPartsAmountSum,
                        deliverySum,
                        totalAmountSum,
                        cashAmountSum,
                        knetAmountSum,
                        paidAmountSum,
                        remainingAmountSum,
                    };
                });
            },

            fetchData() {
                this.isloading = true;
                axios.get('report/getData', {
                        params: {
                            start_date: this.start_date,
                            end_date: this.end_date,
                        }
                    })
                    .then((response) => {
                        this.departments = response.data.departments;
                        this.technicians = response.data.technicians;
                        this.orders = response.data.orders;
                        this.invoices = response.data.invoices;
                        this.aggregateData();
                        this.isloading = false;
                    })
            }
        }
    }
</script>
