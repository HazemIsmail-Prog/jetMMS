<x-app-layout>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.daily_review') }}
            </h2>
            <x-button>
                <span>{{ __('messages.export_to_excel') }}</span>
            </x-button>
        </div>
    </x-slot>


    <div x-data="dailyReviewReport()">
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
            <div class="my-5" x-show="preparedTechniciansData.filter(technician => technician.department_id === department.id && technician.visible).length > 0">
                <h3 class="mb-2 font-semibold text-xl" x-text="department.name"></h3>
                
                <div class="rounded-lg overflow-clip">

                    <!-- header -->
                    <div class="grid grid-cols-12 grid-rows-2 header-row">
                        <div class="table-cell col-span-5 row-span-2">{{ __('messages.technician') }}</div>
                        <div class="table-cell col-span-2 col-start-6">{{ __('messages.invoices') }}</div>
                        <div class="table-cell col-span-3 col-start-8">{{ __('messages.cost_centers') }}</div>
                        <div class="table-cell col-span-2 col-start-11">{{ __('messages.accounts') }}</div>
                        <div class="table-cell col-start-6 row-start-2">{{ __('messages.amount') }}</div>
                        <div class="table-cell col-start-7 row-start-2">{{ __('messages.parts_difference') }}</div>
                        <div class="table-cell col-start-8 row-start-2">{{ __('messages.services') }}</div>
                        <div class="table-cell col-start-9 row-start-2">{{ __('messages.parts') }}</div>
                        <div class="table-cell col-start-10 row-start-2">{{ __('messages.delivery') }}</div>
                        <div class="table-cell col-start-11 row-start-2">{{ __('messages.income_account_id') }}</div>
                        <div class="table-cell col-start-12 row-start-2">{{ __('messages.cost_account_id') }}</div>
                    </div>

                    <!-- technicians -->
                    <template x-for="title in titles" :key="title.id">
                        <div>
                            <template x-for="technician in preparedTechniciansData.filter(technician => technician.department_id === department.id && technician.title_id === title.id)" :key="technician.id">
                                <div class="grid grid-cols-12" x-show="technician.visible">
                                    <div  class="table-cell col-span-5" x-text="technician.name"></div>
                                    <div  class="table-cell" x-text="formatNumber(technician.invoice_total)"></div>
                                    <div  class="table-cell" x-bind:class="technician.internal_parts_vouchers_total < 0 ? 'text-red-500' : ''" x-text="formatNumber(technician.internal_parts_vouchers_total)"></div>
                                    <div  class="table-cell" x-text="formatNumber(technician.services_vouchers_total)"></div>
                                    <div  class="table-cell" x-text="formatNumber(technician.parts_vouchers_total)"></div>
                                    <div  class="table-cell" x-text="formatNumber(technician.delivery_vouchers_total)"></div>
                                    <div  class="table-cell" x-text="formatNumber(technician.income_vouchers_total)"></div>
                                    <div  class="table-cell" x-text="formatNumber(technician.cost_vouchers_total)"></div>
                                </div>
                            </template>

                            <!-- title -->
                            <div class="grid grid-cols-12 divider-row" x-show="preparedTechniciansData.filter(technician => technician.department_id === department.id && technician.title_id === title.id && technician.visible).length > 0">
                                <div class="table-cell col-span-5" x-text="title.name_ar"></div>
                                <div class="table-cell" x-text="formatNumber(getDepartmentTotalByTitle(department.id, title.id).invoice_total)"></div>
                                <div class="table-cell" x-bind:class="getDepartmentTotalByTitle(department.id, title.id).internal_parts_vouchers_total < 0 ? 'text-red-500' : ''" x-text="formatNumber(getDepartmentTotalByTitle(department.id, title.id).internal_parts_vouchers_total)"></div>
                                <div class="table-cell" x-text="formatNumber(getDepartmentTotalByTitle(department.id, title.id).services_vouchers_total)"></div>
                                <div class="table-cell" x-text="formatNumber(getDepartmentTotalByTitle(department.id, title.id).parts_vouchers_total)"></div>
                                <div class="table-cell" x-text="formatNumber(getDepartmentTotalByTitle(department.id, title.id).delivery_vouchers_total)"></div>
                                <div class="table-cell" x-text="formatNumber(getDepartmentTotalByTitle(department.id, title.id).income_vouchers_total)"></div>
                                <div class="table-cell" x-text="formatNumber(getDepartmentTotalByTitle(department.id, title.id).cost_vouchers_total)"></div>
                            </div>
                        </div>
                    </template>


                    <!-- department footer -->
                    <div class="grid grid-cols-12 footer-row">
                        <div class="table-cell col-span-5">{{ __('messages.total') }}</div>
                        <div class="table-cell" x-text="formatNumber(getDepartmentTotal(department.id).invoice_total)"></div>
                        <div class="table-cell" x-bind:class="getDepartmentTotal(department.id).internal_parts_vouchers_total < 0 ? 'text-red-500' : ''" x-text="formatNumber(getDepartmentTotal(department.id).internal_parts_vouchers_total)"></div>
                        <div class="table-cell" x-text="formatNumber(getDepartmentTotal(department.id).services_vouchers_total)"></div>
                        <div class="table-cell" x-text="formatNumber(getDepartmentTotal(department.id).parts_vouchers_total)"></div>
                        <div class="table-cell" x-text="formatNumber(getDepartmentTotal(department.id).delivery_vouchers_total)"></div>
                        <div class="table-cell" x-text="formatNumber(getDepartmentTotal(department.id).income_vouchers_total)"></div>
                        <div class="table-cell" x-text="formatNumber(getDepartmentTotal(department.id).cost_vouchers_total)"></div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>



<script>
    function dailyReviewReport() {
        return {
            isloading: false,
            departments:[],
            titles:[],
            technicians:[],
            invoices:[],
            invoice_details:[],
            invoice_part_details:[],
            voucher_details:[],
            preparedTechniciansData:[],
            // start_date: '2024-08-18', // yesterday date
            // end_date: '2024-08-18',
            start_date: new Date(new Date().setDate(new Date().getDate() - 1)).toISOString().split('T')[0], // yesterday date
            end_date: new Date(new Date().setDate(new Date().getDate() - 1)).toISOString().split('T')[0],
            numberFormatter: new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            }),

            init() {
                this.fetchData()
            },

            fetchData() {
                this.isloading = true;
                this.departments = [];
                this.technicians = [];
                this.invoices = [];
                this.invoice_details = [];
                this.invoice_part_details = [];
                this.voucher_details = [];
                this.preparedTechniciansData = [];
                axios.get('/accounts/reports/daily_review', {
                        params: {
                            start_date: this.start_date,
                            end_date: this.end_date,
                        }
                    })
                    .then((response) => {
                        this.departments = response.data.departments;
                        this.titles = response.data.titles;
                        this.technicians = response.data.technicians;
                        this.invoices = response.data.invoices;
                        this.invoice_details = response.data.invoice_details;
                        this.invoice_part_details = response.data.invoice_part_details;
                        this.voucher_details = response.data.voucher_details;
                        this.preparedTechniciansData = this.getPreparedTechniciansData();
                        this.isloading = false;
                    })
                    .catch((error) => {
                        alert(error.response.data.message);
                    })
                    .finally(() => {
                        this.isloading = false;
                    });
            },

            getDepartmentTechnicians(departmentId){
                return this.technicians.filter(technician => technician.department_id === departmentId);
            },

            getDepartmentTotal(departmentId){
                let totals = {
                    invoice_total: 0,
                    services_vouchers_total: 0,
                    parts_vouchers_total: 0,
                    delivery_vouchers_total: 0,
                    income_vouchers_total: 0,
                    cost_vouchers_total: 0,
                    internal_parts_vouchers_total: 0,
                };
                this.preparedTechniciansData.forEach(technician => {
                    if(technician.department_id === departmentId){
                        totals.invoice_total += technician.invoice_total;
                        totals.services_vouchers_total += technician.services_vouchers_total;
                        totals.parts_vouchers_total += technician.parts_vouchers_total;
                        totals.delivery_vouchers_total += technician.delivery_vouchers_total;
                        totals.income_vouchers_total += technician.income_vouchers_total;
                        totals.cost_vouchers_total += technician.cost_vouchers_total;
                        totals.internal_parts_vouchers_total += technician.internal_parts_vouchers_total;
                    }
                });
                return totals;
            },


            getDepartmentTotalByTitle(departmentId, titleId){
                let totals = {
                    invoice_total: 0,
                    services_vouchers_total: 0,
                    parts_vouchers_total: 0,
                    delivery_vouchers_total: 0,
                    income_vouchers_total: 0,
                    cost_vouchers_total: 0,
                    internal_parts_vouchers_total: 0,
                };
                this.preparedTechniciansData.forEach(technician => {
                    if(technician.department_id === departmentId && technician.title_id === titleId){
                        totals.invoice_total += technician.invoice_total;
                        totals.services_vouchers_total += technician.services_vouchers_total;
                        totals.parts_vouchers_total += technician.parts_vouchers_total;
                        totals.delivery_vouchers_total += technician.delivery_vouchers_total;
                        totals.income_vouchers_total += technician.income_vouchers_total;
                        totals.cost_vouchers_total += technician.cost_vouchers_total;
                        totals.internal_parts_vouchers_total += technician.internal_parts_vouchers_total;
                    }
                });
                return totals;
            },


            getPreparedTechniciansData(){
                return this.technicians.map(technician => {


                    const invoices = this.invoices.filter(invoice => invoice.technician_id === technician.id);
                    const invoice_delivery = invoices.reduce((total, invoice) => total + invoice.delivery, 0);
                    const invoice_discount = invoices.reduce((total, invoice) => total + invoice.discount, 0);
                    const invoice_details = this.invoice_details.filter(invoice_detail => invoices.some(invoice => invoice.id === invoice_detail.invoice_id));
                    const invoice_part_details = this.invoice_part_details.filter(invoice_part_detail => invoices.some(invoice => invoice.id === invoice_part_detail.invoice_id));
                    const totalInvoiceDetails = invoice_details.reduce((total, invoice_detail) => total + invoice_detail.total_amount, 0);
                    const totalInvoicePartDetails = invoice_part_details.reduce((total, invoice_part_detail) => total + invoice_part_detail.total_amount, 0);
                    const invoice_total = totalInvoiceDetails + totalInvoicePartDetails + invoice_delivery - invoice_discount;



                    const income_account_id = this.departments.find(d => d.id === technician.department_id).income_account_id;
                    const cost_account_id = this.departments.find(d => d.id === technician.department_id).cost_account_id;
                    const internal_parts_account_id = this.departments.find(d => d.id === technician.department_id).internal_parts_account_id;
                    const vouchers = this.voucher_details.filter(voucher => voucher.user_id === technician.id);
                    const services_vouchers_total = vouchers
                        .filter(voucher => voucher.cost_center_id === 1)
                        .reduce((total, voucher) => total + (parseFloat(voucher.debit) - parseFloat(voucher.credit)), 0);
                    const parts_vouchers_total = vouchers
                        .filter(voucher => voucher.cost_center_id === 2)
                        .reduce((total, voucher) => total + (parseFloat(voucher.debit) - parseFloat(voucher.credit)), 0);
                    const delivery_vouchers_total = vouchers
                        .filter(voucher => voucher.cost_center_id === 3)
                        .reduce((total, voucher) => total + (parseFloat(voucher.debit) - parseFloat(voucher.credit)), 0);

                    const income_vouchers_total = vouchers
                        .filter(voucher => voucher.account_id === income_account_id)
                        .reduce((total, voucher) => total + (parseFloat(voucher.debit) - parseFloat(voucher.credit)), 0);

                    const cost_vouchers_total = vouchers
                        .filter(voucher => voucher.account_id === cost_account_id)
                        .reduce((total, voucher) => total + (parseFloat(voucher.debit) - parseFloat(voucher.credit)), 0);

                    const internal_parts_vouchers_total = vouchers
                        .filter(voucher => voucher.account_id === internal_parts_account_id)
                        .reduce((total, voucher) => total + (parseFloat(voucher.debit) - parseFloat(voucher.credit)), 0);

                    const visible = invoice_total + services_vouchers_total + parts_vouchers_total + delivery_vouchers_total + income_vouchers_total + cost_vouchers_total + internal_parts_vouchers_total !== 0;

                    return {
                        id: technician.id,
                        department_id: technician.department_id,
                        name: technician.name,
                        title_id: technician.title_id,
                        invoice_total: Math.abs(invoice_total),
                        services_vouchers_total: Math.abs(services_vouchers_total),
                        parts_vouchers_total: Math.abs(parts_vouchers_total),
                        delivery_vouchers_total: Math.abs(delivery_vouchers_total),
                        income_vouchers_total: Math.abs(income_vouchers_total),
                        cost_vouchers_total: Math.abs(cost_vouchers_total),
                        internal_parts_vouchers_total: internal_parts_vouchers_total,
                        visible: visible,
                    }
                });
            },

            formatNumber(value) {
                if(value === 0){
                    return '-';
                }
                return this.numberFormatter.format(value);
            },



        }
    }
</script>
