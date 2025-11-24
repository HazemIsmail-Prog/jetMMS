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
                    <x-th>{{ __('messages.total_credit') }}</x-th>
                    <x-th>{{ __('messages.total_debit') }}</x-th>
                    <x-th>{{__('messages.total_income')}}</x-th>

                </tr>
            </x-thead>
            <tbody>
                <template x-for="voucherDetail in voucherDetails" :key="voucherDetail.account_id">
                    <x-tr>
                        <x-td x-text="getOtherIncomeCategoryNameByIncomeAccountId(voucherDetail.account_id) || getDepartmentNameByIncomeAccountId(voucherDetail.account_id)"></x-td>
                        <x-td x-text="formatNumber(voucherDetail.total_credit)"></x-td>
                        <x-td x-text="formatNumber(voucherDetail.total_debit)"></x-td>
                        <x-td x-text="formatNumber(voucherDetail.total_credit - voucherDetail.total_debit)"></x-td>
                    </x-tr>
                </template>
            </tbody>
            <x-tfoot>
                <tr>
                    <x-th>{{ __('messages.total') }}</x-th>
                    <x-th x-text="formatNumber(getPageTotal().total_credit)"></x-th>
                    <x-th x-text="formatNumber(getPageTotal().total_debit)"></x-th>
                    <x-th x-text="formatNumber(getPageTotal().total_income)"></x-th>
                </tr>
            </x-tfoot>
        </x-table>

    </div>
</x-app-layout>



<script>
    function incomeReport() {
        return {
            otherIncomeCategories:[],
            departments:[],
            isloading: false,
            exporting: false,
            voucherDetails:[],
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
                let total_credit = 0;
                let total_debit = 0;
                let total_income = 0;
                this.voucherDetails.forEach(voucherDetail => {
                    total_credit += parseFloat(voucherDetail.total_credit || 0);
                    total_debit += parseFloat(voucherDetail.total_debit || 0);
                    total_income += parseFloat(voucherDetail.total_credit - voucherDetail.total_debit || 0);
                });
                return {
                    total_credit: total_credit,
                    total_debit: total_debit,
                    total_income: total_income
                };
            },

            getOtherIncomeCategoryNameByIncomeAccountId(incomeAccountId) {
                return this.otherIncomeCategories.find(otherIncomeCategory => otherIncomeCategory.income_account_id === incomeAccountId)?.name;
            },

            getDepartmentNameByIncomeAccountId(incomeAccountId) {
                return this.departments.find(department => department.income_account_id === incomeAccountId)?.name;
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
                        this.voucherDetails = response.data.voucher_details;
                        this.otherIncomeCategories = response.data.otherIncomeCategories;
                        this.departments = response.data.departments;
                    })
                    .catch((error) => {
                        alert(error.response.data.message);
                    })
                    .finally(() => {
                        this.isloading = false;
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
