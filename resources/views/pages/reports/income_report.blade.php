<x-app-layout>
    <x-slot name="title">
        {{ __('messages.income_report') }}
    </x-slot>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.income_report') }}
            </h2>
        </div>
    </x-slot>


    <div x-data="incomeReport()">

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

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{__('messages.total_income')}}</x-th>

                </tr>
            </x-thead>
            <tbody>
                <template x-for="department in departments" :key="department.id">
                    <x-tr x-show="getDepartmentTotals(department.id).total > 0">
                        <x-td x-text="department.name"></x-td>
                        <x-td x-text="formatNumber(getDepartmentTotals(department.id).total)"></x-td>
                    </x-tr>
                </template>
                <template x-for="incomeInvoice in incomeInvoices" :key="incomeInvoice.other_income_category_id">
                    <x-tr>
                        <x-td x-text="getOtherIncomeCategoryNameById(incomeInvoice.other_income_category_id)"></x-td>
                        <x-td x-text="formatNumber(incomeInvoice.total_amount)"></x-td>
                    </x-tr>
                </template>
            </tbody>
            <x-tfoot>
                <tr>
                    <x-th>{{ __('messages.total') }}</x-th>
                    <x-th x-text="formatNumber(getPageTotal())"></x-th>
                </tr>
            </x-tfoot>
        </x-table>

    </div>
</x-app-layout>



<script>
    function incomeReport() {
        return {
            otherIncomeCategories:@js($otherIncomeCategories),
            departments:@js($departments),
            isloading: false,
            exporting: false,
            incomeInvoices:[],
            departmentInvoices:[],
            start_date: new Date(new Date().setDate(new Date().getDate() - 1)).toISOString().split('T')[0], // yesterday date
            end_date: new Date(new Date().setDate(new Date().getDate() - 1)).toISOString().split('T')[0],
            numberFormatter: new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            }),

            init() {
                this.fetchData()
            },

            getPageTotal() {
                let invoice_detail = 0;
                let invoice_part_detail = 0;
                let delivery = 0;
                let discount = 0;
                this.departmentInvoices.forEach(invoice => {
                    invoice_detail += parseFloat(invoice.invoice_details_total_amount || 0);
                    invoice_part_detail += parseFloat(invoice.invoice_part_details_total_amount || 0);
                    delivery += parseFloat(invoice.delivery || 0);
                    discount += parseFloat(invoice.discount || 0);
                });
                let total = invoice_detail + invoice_part_detail + delivery - discount;
                this.incomeInvoices.forEach(incomeInvoice => {
                    total += parseFloat(incomeInvoice.total_amount || 0);
                });
                return total;
            },

            getOtherIncomeCategoryNameById(id) {
                return this.otherIncomeCategories.find(otherIncomeCategory => otherIncomeCategory.id === id)?.name;
            },

            getDepartmentTotals(departmentId) {
                let invoice_detail = 0;
                let invoice_part_detail = 0;
                let delivery = 0;
                let discount = 0;
                this.departmentInvoices.forEach(invoice => {
                    if(invoice.department_id === departmentId){
                        invoice_detail += parseFloat(invoice.invoice_details_total_amount || 0);
                        invoice_part_detail += parseFloat(invoice.invoice_part_details_total_amount || 0);
                        delivery += parseFloat(invoice.delivery || 0);
                        discount += parseFloat(invoice.discount || 0);
                    }
                });
                return {
                    invoice_detail: invoice_detail,
                    invoice_part_detail: invoice_part_detail,
                    delivery: delivery,
                    discount: discount,
                    total: invoice_detail + invoice_part_detail + delivery - discount
                };
            },

            fetchData() {
                this.isloading = true;
                this.exporting = false;
                this.incomeInvoices = [];
                this.departmentInvoices = [];
                axios.get('/accounts/reports/income_report', {
                        params: {
                            start_date: this.start_date,
                            end_date: this.end_date,
                        }
                    })
                    .then((response) => {
                        this.incomeInvoices = response.data.income_invoices;
                        this.departmentInvoices = response.data.department_invoices;
                    })
                    .catch((error) => {
                        alert(error.response.data.message);
                    })
                    .finally(() => {
                        this.isloading = false;
                    });
            },

            formatNumber(value) {
                // return value;
                if(value === 0){
                    return '-';
                }
                return this.numberFormatter.format(value);
            },

        }
    }
</script>
