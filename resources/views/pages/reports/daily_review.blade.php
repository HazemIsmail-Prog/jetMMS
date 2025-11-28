<x-app-layout>
    <x-slot name="title">
        {{ __('messages.daily_review') }}
    </x-slot>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.daily_review') }}
            </h2>
            <span id="excel"></span>
        </div>
    </x-slot>


    <div x-data="dailyReviewReport()">

        <template x-teleport="#excel">
            <x-button x-on:click="exportToExcel" x-bind:disabled="exporting" >
                <span x-show="!exporting">{{ __('messages.export_to_excel') }}</span>
                <span x-show="exporting" class="animate-pulse">{{ __('messages.loading') }}</span>
            </x-button>
        </template>

        <!-- filters -->
        <div class=" flex items-center justify-between mb-3">

            <div class=" flex items-end gap-3 no-print">
                <div class=" col-span-1 md:col-span-2 xl:col-span-4">
                    <x-label for="start_date">{{ __('messages.date') }}</x-label>
                    <x-input type="date" class="w-full" id="start_date" x-model="start_date" />
                    <x-input type="date" class="w-full" id="end_date" x-model="end_date" />
                </div>

                <x-button type="button" x-on:click="fetchData">{{ __('messages.update') }}</x-button>
            </div>

            <div x-show="isloading" class="text-gray-800 dark:text-gray-300">
                {{ __('messages.loading') }}
            </div>
        </div>

        <!-- data -->
        <div class="overflow-x-scroll">

            <!-- departments -->
            <template x-for="department in departments" :key="department.id">
                <div class="my-5" x-show="getDepartmentTotal(department.id).visible">
                    <h3 class="mb-2 font-semibold text-xl text-gray-700 dark:text-gray-100" x-text="department.name"></h3>
                
                    <!-- header -->
                    <div class="flex gap-1 mb-1 header-row w-fit">
                        <div class="table-cell !w-[300px]">{{ __('messages.technician') }}</div>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1 header-row">
                                <div class="table-cell" style="width: calc(300px + 4px);">{{ __('messages.invoices') }}</div>
                                <div class="table-cell" style="width: calc(450px + 8px);">{{ __('messages.cost_centers') }}</div>
                                <div class="table-cell" style="width: calc(300px + 4px);">{{ __('messages.accounts') }}</div>
                            </div>
                            <div class="flex items-center gap-1 header-row">
                                <div class="table-cell w-[150px]">{{ __('messages.amount') }}</div>
                                <div class="table-cell w-[150px]">{{ __('messages.parts_difference') }}</div>
                                <div class="table-cell w-[150px]">{{ __('messages.services') }}</div>
                                <div class="table-cell w-[150px]">{{ __('messages.parts') }}</div>
                                <div class="table-cell w-[150px]">{{ __('messages.delivery') }}</div>
                                <div class="table-cell w-[150px]">{{ __('messages.income_account_id') }}</div>
                                <div class="table-cell w-[150px]">{{ __('messages.cost_account_id') }}</div>
                            </div>

                        </div>
                    </div>

                    <!-- technicians -->
                    <template x-for="title in titles" :key="title.id">
                        <div x-show="getTitleTotals(department.id, title.id).visible">
                            <template x-for="technician in getTechniciansSortedByName(department.id, title.id)" :key="technician.id">
                                <div class="flex gap-1 mb-1 hover:bg-gray-100 dark:hover:bg-gray-900 w-fit">
                                    <div  class="table-cell w-[300px]" x-text="technician.name"></div>
                                    <div  class="table-cell w-[150px]" x-text="formatNumber(technician.invoices_total)"></div>
                                    <div  class="table-cell w-[150px]" x-text="formatNumber(technician.internal_parts_account_total)" x-bind:class="technician.internal_parts_account_total < 0 ? '!text-red-500' : ''"></div>
                                    <div  class="table-cell w-[150px]" x-text="formatNumber(technician.services_vouchers_total)"></div>
                                    <div  class="table-cell w-[150px]" x-text="formatNumber(technician.parts_vouchers_total)"></div>
                                    <div  class="table-cell w-[150px]" x-text="formatNumber(technician.delivery_vouchers_total)"></div>
                                    <div  class="table-cell w-[150px]" x-text="formatNumber(technician.income_account_total)"></div>
                                    <div  class="table-cell w-[150px]" x-text="formatNumber(technician.cost_account_total)"></div>
                                </div>
                            </template>

                            <!-- title -->
                            <div class="flex gap-1 mb-1 divider-row mb-1 w-fit">
                                <div class="table-cell w-[300px]" x-text="title.name_ar"></div>
                                <div class="table-cell w-[150px]" x-text="formatNumber(getTitleTotals(department.id, title.id).invoices_total)"></div>
                                <div class="table-cell w-[150px]" x-text="formatNumber(getTitleTotals(department.id, title.id).internal_parts_account_total)" x-bind:class="getTitleTotals(department.id, title.id).internal_parts_account_total < 0 ? '!text-red-500' : ''"></div>
                                <div class="table-cell w-[150px]" x-text="formatNumber(getTitleTotals(department.id, title.id).services_vouchers_total)"></div>
                                <div class="table-cell w-[150px]" x-text="formatNumber(getTitleTotals(department.id, title.id).parts_vouchers_total)"></div>
                                <div class="table-cell w-[150px]" x-text="formatNumber(getTitleTotals(department.id, title.id).delivery_vouchers_total)"></div>
                                <div class="table-cell w-[150px]" x-text="formatNumber(getTitleTotals(department.id, title.id).income_account_total)"></div>
                                <div class="table-cell w-[150px]" x-text="formatNumber(getTitleTotals(department.id, title.id).cost_account_total)"></div>
                            </div>
                        </div>
                    </template>

                    <!-- department footer -->
                    <div class="flex gap-1 footer-row mb-1 w-fit">
                        <div class="table-cell w-[300px]">{{ __('messages.total') }}</div>
                        <div class="table-cell w-[150px]" x-text="formatNumber(getDepartmentTotal(department.id).invoices_total)"></div>
                        <div class="table-cell w-[150px]" x-text="formatNumber(getDepartmentTotal(department.id).internal_parts_account_total)" x-bind:class="getDepartmentTotal(department.id).internal_parts_account_total < 0 ? '!text-red-500' : ''"></div>
                        <div class="table-cell w-[150px]" x-text="formatNumber(getDepartmentTotal(department.id).services_vouchers_total)"></div>
                        <div class="table-cell w-[150px]" x-text="formatNumber(getDepartmentTotal(department.id).parts_vouchers_total)"></div>
                        <div class="table-cell w-[150px]" x-text="formatNumber(getDepartmentTotal(department.id).delivery_vouchers_total)"></div>
                        <div class="table-cell w-[150px]" x-text="formatNumber(getDepartmentTotal(department.id).income_account_total)"></div>
                        <div class="table-cell w-[150px]" x-text="formatNumber(getDepartmentTotal(department.id).cost_account_total)"></div>
                    </div>


                </div>
            </template>

            <!-- income invoices -->
            <div class="my-5" x-show="incomeInvoices.length > 0">
                <h3 class="mb-2 font-semibold text-xl text-gray-700 dark:text-gray-100">{{ __('messages.income_invoices') }}</h3>
                    
                <!-- header -->
                <div class="flex gap-1 mb-1 header-row w-fit">
                    <div class="table-cell !w-[300px]">{{ __('messages.technician') }}</div>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 header-row">
                            <div class="table-cell" style="width: calc(300px + 4px);">{{ __('messages.invoices') }}</div>
                            <div class="table-cell" style="width: calc(450px + 8px);">{{ __('messages.cost_centers') }}</div>
                            <div class="table-cell" style="width: calc(300px + 4px);">{{ __('messages.accounts') }}</div>
                        </div>
                        <div class="flex items-center gap-1 header-row">
                            <div class="table-cell w-[150px]">{{ __('messages.amount') }}</div>
                            <div class="table-cell w-[150px]">{{ __('messages.parts_difference') }}</div>
                            <div class="table-cell w-[150px]">{{ __('messages.services') }}</div>
                            <div class="table-cell w-[150px]">{{ __('messages.parts') }}</div>
                            <div class="table-cell w-[150px]">{{ __('messages.delivery') }}</div>
                            <div class="table-cell w-[150px]">{{ __('messages.income_account_id') }}</div>
                            <div class="table-cell w-[150px]">{{ __('messages.cost_account_id') }}</div>
                        </div>

                    </div>
                </div>

                <!-- other income categories -->
                <div>
                    <template x-for="incomeInvoice in incomeInvoices" :key="incomeInvoice.other_income_category_id">
                        <div class="flex gap-1 mb-1 hover:bg-gray-100 dark:hover:bg-gray-900 w-fit">
                            <div  class="table-cell w-[300px]" x-text="getOtherIncomeCategoryNameById(incomeInvoice.other_income_category_id)"></div>
                            <div  class="table-cell w-[150px]" x-text="formatNumber(incomeInvoice.total_amount)"></div>
                            <div  class="table-cell w-[150px]"></div>
                            <div  class="table-cell w-[150px]"></div>
                            <div  class="table-cell w-[150px]"></div>
                            <div  class="table-cell w-[150px]"></div>
                            <div  class="table-cell w-[150px]" x-text="formatNumber(incomeInvoice.total_amount)"></div>
                            <div  class="table-cell w-[150px]"></div>
                        </div>
                    </template>
                </div>
                
                <!-- other income categories footer -->
                <div class="flex gap-1 footer-row mb-1 w-fit">
                    <div class="table-cell w-[300px]">{{ __('messages.total') }}</div>
                    <div class="table-cell w-[150px]" x-text="formatNumber(getOtherIncomeCategoryTotal())"></div>
                    <div class="table-cell w-[150px]"></div>
                    <div class="table-cell w-[150px]"></div>
                    <div class="table-cell w-[150px]"></div>
                    <div class="table-cell w-[150px]"></div>
                    <div class="table-cell w-[150px]" x-text="formatNumber(getOtherIncomeCategoryTotal())"></div>
                    <div class="table-cell w-[150px]"></div>
                </div>

            </div>

            <!-- page total -->
            <div class="flex gap-1 footer-row mb-1 w-fit">
                <div class="table-cell w-[300px]">{{ __('messages.total') }}</div>
                <div class="table-cell w-[150px]" x-text="formatNumber(getPageTotal().invoices_total + getOtherIncomeCategoryTotal())"></div>
                <div class="table-cell w-[150px]" x-bind:class="getPageTotal().internal_parts_account_total < 0 ? '!text-red-500' : ''" x-text="formatNumber(getPageTotal().internal_parts_account_total)"></div>
                <div class="table-cell w-[150px]" x-text="formatNumber(getPageTotal().services_vouchers_total)"></div>
                <div class="table-cell w-[150px]" x-text="formatNumber(getPageTotal().parts_vouchers_total)"></div>
                <div class="table-cell w-[150px]" x-text="formatNumber(getPageTotal().delivery_vouchers_total)"></div>
                <div class="table-cell w-[150px]" x-text="formatNumber(getPageTotal().income_account_total + getOtherIncomeCategoryTotal())"></div>
                <div class="table-cell w-[150px]" x-text="formatNumber(getPageTotal().cost_account_total)"></div>
            </div>

        </div>
        
    </div>
</x-app-layout>



<script>
    function dailyReviewReport() {
        return {
            otherIncomeCategories:@js($otherIncomeCategories),
            isloading: false,
            exporting: false,
            incomeInvoices:[],
            departments:[],
            titles:[],
            technicians:[],
            voucher_details:[],
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
                this.exporting = false;
                this.departments = [];
                this.titles = [];
                this.incomeInvoices = [];
                this.technicians = [];
                this.voucher_details = [];
                axios.get('/accounts/reports/daily_review', {
                        params: {
                            start_date: this.start_date,
                            end_date: this.end_date,
                        }
                    })
                    .then((response) => {
                        this.departments = response.data.departments;
                        this.titles = response.data.titles;
                        this.incomeInvoices = response.data.income_invoices;
                        this.technicians = response.data.technicians;
                        this.voucher_details = response.data.voucher_details;
                    })
                    .catch((error) => {
                        alert(error.response.data.message);
                    })
                    .finally(() => {
                        this.isloading = false;
                    });
            },

            getTechniciansSortedByName(departmentId, titleId) {
                return this.technicians
                    .filter(technician => technician.department_id === departmentId && technician.title_id === titleId)
                    .sort((a, b) => {
                        const nameA = a.name ?? '';
                        const nameB = b.name ?? '';
                        return nameA.localeCompare(nameB, undefined, { sensitivity: 'base' });
                    });
            },

            getTitleTotals(departmentId, titleId) {
                let invoices_total = 0;
                let services_vouchers_total = 0;
                let parts_vouchers_total = 0;
                let delivery_vouchers_total = 0;
                let income_account_total = 0;
                let cost_account_total = 0;
                let internal_parts_account_total = 0;

                title_data = this.technicians.filter(technician => technician.department_id === departmentId && technician.title_id === titleId);

                invoices_total = title_data.reduce((total, technician) => total + technician.invoices_total, 0);
                services_vouchers_total = title_data.reduce((total, technician) => total + technician.services_vouchers_total, 0);
                parts_vouchers_total = title_data.reduce((total, technician) => total + technician.parts_vouchers_total, 0);
                delivery_vouchers_total = title_data.reduce((total, technician) => total + technician.delivery_vouchers_total, 0);
                income_account_total = title_data.reduce((total, technician) => total + technician.income_account_total, 0);
                cost_account_total = title_data.reduce((total, technician) => total + technician.cost_account_total, 0);
                internal_parts_account_total = title_data.reduce((total, technician) => total + technician.internal_parts_account_total, 0);

                return {
                    invoices_total: invoices_total,
                    services_vouchers_total: services_vouchers_total,
                    parts_vouchers_total: parts_vouchers_total,
                    delivery_vouchers_total: delivery_vouchers_total,
                    income_account_total: income_account_total,
                    cost_account_total: cost_account_total,
                    internal_parts_account_total: internal_parts_account_total,
                    visible: invoices_total + services_vouchers_total + parts_vouchers_total + delivery_vouchers_total + income_account_total + cost_account_total + internal_parts_account_total !== 0,
                };
            },

            getDepartmentTotal(departmentId) {
                let invoices_total = 0;
                let services_vouchers_total = 0;
                let parts_vouchers_total = 0;
                let delivery_vouchers_total = 0;
                let income_account_total = 0;
                let cost_account_total = 0;
                let internal_parts_account_total = 0;

                department_data = this.technicians.filter(technician => technician.department_id === departmentId);
                
                invoices_total = department_data.reduce((total, technician) => total + technician.invoices_total, 0);
                services_vouchers_total = department_data.reduce((total, technician) => total + technician.services_vouchers_total, 0);
                parts_vouchers_total = department_data.reduce((total, technician) => total + technician.parts_vouchers_total, 0);
                delivery_vouchers_total = department_data.reduce((total, technician) => total + technician.delivery_vouchers_total, 0);
                income_account_total = department_data.reduce((total, technician) => total + technician.income_account_total, 0);
                cost_account_total = department_data.reduce((total, technician) => total + technician.cost_account_total, 0);
                internal_parts_account_total = department_data.reduce((total, technician) => total + technician.internal_parts_account_total, 0);
                
                return {
                    invoices_total: invoices_total,
                    services_vouchers_total: services_vouchers_total,
                    parts_vouchers_total: parts_vouchers_total,
                    delivery_vouchers_total: delivery_vouchers_total,
                    income_account_total: income_account_total,
                    cost_account_total: cost_account_total,
                    internal_parts_account_total: internal_parts_account_total,
                    visible: invoices_total + services_vouchers_total + parts_vouchers_total + delivery_vouchers_total + income_account_total + cost_account_total + internal_parts_account_total !== 0,
                };
            },

            getOtherIncomeCategoryNameById(id) {
                return this.otherIncomeCategories.find(otherIncomeCategory => otherIncomeCategory.id === id)?.name;
            },

            getOtherIncomeCategoryTotal(){
                return this.incomeInvoices.reduce((total, incomeInvoice) => total + parseFloat(incomeInvoice.total_amount), 0);
            },

            getPageTotal(){
                let invoices_total = 0;
                let services_vouchers_total = 0;
                let parts_vouchers_total = 0;
                let delivery_vouchers_total = 0;
                let income_account_total = 0;
                let cost_account_total = 0;
                let internal_parts_account_total = 0;

                invoices_total = this.technicians.reduce((total, technician) => total + technician.invoices_total, 0);
                services_vouchers_total = this.technicians.reduce((total, technician) => total + technician.services_vouchers_total, 0);
                parts_vouchers_total = this.technicians.reduce((total, technician) => total + technician.parts_vouchers_total, 0);
                delivery_vouchers_total = this.technicians.reduce((total, technician) => total + technician.delivery_vouchers_total, 0);
                income_account_total = this.technicians.reduce((total, technician) => total + technician.income_account_total, 0);
                cost_account_total = this.technicians.reduce((total, technician) => total + technician.cost_account_total, 0);
                internal_parts_account_total = this.technicians.reduce((total, technician) => total + technician.internal_parts_account_total, 0);

                return {
                    invoices_total: invoices_total,
                    services_vouchers_total: services_vouchers_total,
                    parts_vouchers_total: parts_vouchers_total,
                    delivery_vouchers_total: delivery_vouchers_total,
                    income_account_total: income_account_total,
                    cost_account_total: cost_account_total,
                    internal_parts_account_total: internal_parts_account_total,
                };
            },

            formatNumber(value) {
                // return value;
                if(value === 0){
                    return '-';
                }
                return this.numberFormatter.format(value);
            },

            exportToExcel() {
                this.exporting = true;
                axios.get('/accounts/reports/daily_review/exportToExcel', {
                    params: {
                        start_date: this.start_date,
                        end_date: this.end_date,
                    },
                    responseType: 'blob'
                })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'Daily_Review.xlsx');
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
