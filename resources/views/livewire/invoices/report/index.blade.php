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

                <x-button type="button" x-on:click="fetchData">{{ __('messages.update') }}</x-button>
            </div>

            <div x-show="isloading">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-10 h-10 animate-spin">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </div>
        </div>


        <template x-for="department in departments" :key="department.id">
            <div x-show="techniciansOfDepartment(department.id).length" class="mb-5">
                <div class=" text-lg font-bold mb-2" x-text="department.name"></div>
                <template x-if="!isloading && techniciansOfDepartment(department.id).length">
                    <x-table>
                        <x-thead>
                            <tr>
                                <x-th>{{ __('messages.name') }}</x-th>
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
                        <template x-for="title in titles" :key="title.id">
                            <tbody x-show="techniciansOfDepartmentAndTitle(department.id,title.id).length">
                                <template x-for="technician in techniciansOfDepartmentAndTitle(department.id,title.id)"
                                    :key="technician.id">
                                    <x-tr>
                                        <x-td x-text="technician.name"></x-td>
                                        <x-td
                                            x-text="technician.ordersCount == 0 ? '-' : technician.ordersCount"></x-td>
                                        <x-td
                                            x-text="technician.invoicesCount == 0 ? '-' : technician.invoicesCount"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.servicesAmountSum)"></x-td>
                                        <x-td class=" !text-right" x-text="formatNumber(technician.discountSum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.servicesAfterDiscountSum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.internalPartsAmountSum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.externalPartsAmountSum)"></x-td>
                                        <x-td class=" !text-right" x-text="formatNumber(technician.deliverySum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.totalAmountSum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.cashAmountSum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.knetAmountSum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.paidAmountSum)"></x-td>
                                        <x-td class=" !text-right"
                                            x-text="formatNumber(technician.remainingAmountSum)"></x-td>
                                    </x-tr>
                                </template>
                                <tr class=" bg-gray-200">
                                    <x-td x-text="title.name_ar"></x-td>
                                    <x-td
                                        x-text="techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.ordersCount, 0)"></x-td>
                                    <x-td
                                        x-text="techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.invoicesCount, 0)"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.servicesAmountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.discountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.servicesAfterDiscountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.internalPartsAmountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.externalPartsAmountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.deliverySum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.totalAmountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.cashAmountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.knetAmountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.paidAmountSum, 0))"></x-td>
                                    <x-td class="!text-right"
                                        x-text="formatNumber(techniciansOfDepartmentAndTitle(department.id,title.id).reduce((sum, technician) => sum + technician.remainingAmountSum, 0))"></x-td>
                                </tr>
                            </tbody>
                        </template>
                        <x-tfoot>
                            <tr>
                                <x-th>{{ __('messages.total') }}</x-th>
                                <x-th x-text="department.ordersCount"></x-th>
                                <x-th x-text="department.invoicesCount"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.servicesAmountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.discountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.servicesAfterDiscountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.internalPartsAmountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.externalPartsAmountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.deliverySum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.totalAmountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.cashAmountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.knetAmountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.paidAmountSum)"></x-th>
                                <x-th class="!text-right" x-text="formatNumber(department.remainingAmountSum)"></x-th>
                            </tr>
                        </x-tfoot>
                    </x-table>
                </template>
            </div>
        </template>


        <template x-if="!isloading">
            <x-table >
    
                <x-thead>
                    <tr>
                        <x-th rowspan="2">{{ __('messages.total') }}</x-th>
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
                    <tr>
                        <x-th x-text="technicians.reduce((sum, technician) => sum + technician.ordersCount, 0)"></x-th>
                        <x-th x-text="technicians.reduce((sum, technician) => sum + technician.invoicesCount, 0)"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.servicesAmountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.discountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.servicesAfterDiscountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.internalPartsAmountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.externalPartsAmountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.deliverySum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.totalAmountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.cashAmountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.knetAmountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.paidAmountSum, 0))"></x-th>
                        <x-th class="!text-right"
                            x-text="formatNumber(technicians.reduce((sum, technician) => sum + technician.remainingAmountSum, 0))"></x-th>
                    </tr>
                </x-thead>
    
            </x-table>
        </template>





    </div>
</x-app-layout>

<script>
    function invoicesReport() {
        return {
            isloading: false,
            departments: [],
            titles: [],
            technicians: [],
            orders: [],
            start_date: '2024-06-01',
            end_date: '2024-06-01',
            // start_date: new Date().toISOString().split('T')[0], // today date
            // end_date: new Date().toISOString().split('T')[0],
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

            aggregateTechnicaionsData() {
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
                        titleName: this.titles.filter(title => title.id === technician
                            .title_id)[0].name_ar,
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

            aggregateDepartmentsData() {
                this.departments = this.departments.map(department => {
                    return {
                        ...department,
                        ordersCount: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.ordersCount, 0),
                        invoicesCount: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.invoicesCount, 0),
                        servicesAmountSum: this.techniciansOfDepartment(department.id).reduce((sum,
                            technician) => sum + technician.servicesAmountSum, 0),
                        discountSum: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.discountSum, 0),
                        servicesAfterDiscountSum: this.techniciansOfDepartment(department.id).reduce((sum,
                            technician) => sum + technician.servicesAfterDiscountSum, 0),
                        internalPartsAmountSum: this.techniciansOfDepartment(department.id).reduce((sum,
                            technician) => sum + technician.internalPartsAmountSum, 0),
                        externalPartsAmountSum: this.techniciansOfDepartment(department.id).reduce((sum,
                            technician) => sum + technician.externalPartsAmountSum, 0),
                        deliverySum: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.deliverySum, 0),
                        totalAmountSum: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.totalAmountSum, 0),
                        cashAmountSum: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.cashAmountSum, 0),
                        knetAmountSum: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.knetAmountSum, 0),
                        paidAmountSum: this.techniciansOfDepartment(department.id).reduce((sum, technician) =>
                            sum + technician.paidAmountSum, 0),
                        remainingAmountSum: this.techniciansOfDepartment(department.id).reduce((sum,
                            technician) => sum + technician.remainingAmountSum, 0),
                    };
                });
            },

            techniciansOfDepartment(departmentId) {
                return this.technicians.filter(technician => technician.department_id === departmentId);
            },

            techniciansOfDepartmentAndTitle(departmentId, titleId) {
                return this.technicians.filter(technician => technician.department_id === departmentId && technician
                    .title_id === titleId);
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
                        this.titles = response.data.titles;
                        this.technicians = response.data.technicians;
                        this.orders = response.data.orders;
                        this.invoices = response.data.invoices;
                        this.aggregateTechnicaionsData();
                        this.aggregateDepartmentsData();
                        this.isloading = false;
                    })
            }
        }
    }
</script>
