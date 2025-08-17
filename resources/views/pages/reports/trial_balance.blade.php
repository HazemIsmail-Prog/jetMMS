<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.trial_balance') }}
        </h2>
    </x-slot>

    <div x-data="trialBalanceReport()">
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


        <x-table>
            <x-thead>
                <tr>
                    <x-borderd-th rowspan="2" class="!w-1/12">{{ __('messages.account') }}</x-borderd-th>
                    <x-borderd-th colspan="2" class="!w-1/12">{{ __('messages.opening_balance') }}</x-borderd-th>
                    <x-borderd-th colspan="2" class="!w-1/12">{{ __('messages.transactions_balance') }}</x-borderd-th>
                    <x-borderd-th colspan="2" class="!w-1/12">{{ __('messages.closing_balance') }}</x-borderd-th>
                </tr>
                <tr>
                    <x-borderd-th class="!w-1/12">{{ __('messages.debit') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.credit') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.debit') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.credit') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.debit') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.credit') }}</x-borderd-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="account in accounts" :key="account.id">
                    <x-tr>
                        <x-borderd-td x-text="account.name"></x-borderd-td>
                        <x-borderd-td x-text="formatNumber(getAccountData(account.id).opening_debit)"></x-borderd-td>
                        <x-borderd-td x-text="formatNumber(getAccountData(account.id).opening_credit)"></x-borderd-td>
                        <x-borderd-td class=" bg-gray-200 dark:bg-gray-700" x-text="formatNumber(getAccountData(account.id).transactions_debit)"></x-borderd-td>
                        <x-borderd-td class=" bg-gray-200 dark:bg-gray-700" x-text="formatNumber(getAccountData(account.id).transactions_credit)"></x-borderd-td>
                        <x-borderd-td x-text="formatNumber(getAccountData(account.id).closing_debit)"></x-borderd-td>
                        <x-borderd-td x-text="formatNumber(getAccountData(account.id).closing_credit)"></x-borderd-td>
                    </x-tr>
                </template>
            </tbody>

            <x-tfoot>
                <tr>
                    <x-borderd-th>{{ __('messages.total') }}</x-borderd-th>
                    <x-borderd-th x-text="formatNumber(getTotals().opening_debit)"></x-borderd-th>
                    <x-borderd-th x-text="formatNumber(getTotals().opening_credit)"></x-borderd-th>
                    <x-borderd-th x-text="formatNumber(getTotals().transactions_debit)"></x-borderd-th>
                    <x-borderd-th x-text="formatNumber(getTotals().transactions_credit)"></x-borderd-th>
                    <x-borderd-th x-text="formatNumber(getTotals().closing_debit)"></x-borderd-th>
                    <x-borderd-th x-text="formatNumber(getTotals().closing_credit)"></x-borderd-th>
                </tr>
            </x-tfoot>

        </x-table>

    </div>
</x-app-layout>

<script>
    function trialBalanceReport() {
        return {
            isloading: false,
            accounts:[],
            opening_voucher_details:[],
            transactions_voucher_details:[],
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
                if(value === 0){
                    return '-';
                }
                return this.numberFormatter.format(value);
            },

            getAccountData(accountId){
                const opening_voucher_details = this.opening_voucher_details.find(voucher_detail => voucher_detail.account_id === accountId);
                const transactions_voucher_details = this.transactions_voucher_details.find(voucher_detail => voucher_detail.account_id === accountId);

                const original_opening_debit = opening_voucher_details && opening_voucher_details.opening_debit > 0 ? opening_voucher_details.opening_debit : 0;
                const original_opening_credit = opening_voucher_details && opening_voucher_details.opening_credit > 0 ? opening_voucher_details.opening_credit : 0;

                const original_transactions_debit = transactions_voucher_details && transactions_voucher_details.transactions_debit > 0 ? transactions_voucher_details.transactions_debit : 0;
                const original_transactions_credit = transactions_voucher_details && transactions_voucher_details.transactions_credit > 0 ? transactions_voucher_details.transactions_credit : 0;

                const results = {
                    opening_debit: 0,
                    opening_credit: 0,
                    transactions_debit: 0,
                    transactions_credit: 0,
                    closing_debit: 0,
                    closing_credit: 0
                }
                
                results.transactions_debit = original_transactions_debit;
                results.transactions_credit = original_transactions_credit;
                
                if(parseFloat(original_opening_debit) > parseFloat(original_opening_credit)){
                    results.opening_debit = parseFloat(original_opening_debit) - parseFloat(original_opening_credit);
                }
                if(parseFloat(original_opening_credit) > parseFloat(original_opening_debit)){
                    results.opening_credit = parseFloat(original_opening_credit) - parseFloat(original_opening_debit);
                }

                const closing_debit = parseFloat(results.opening_debit) + parseFloat(results.transactions_debit);
                const closing_credit = parseFloat(results.opening_credit) + parseFloat(results.transactions_credit);

                if(parseFloat(closing_debit) > parseFloat(closing_credit)){
                    results.closing_debit = parseFloat(closing_debit) - parseFloat(closing_credit);
                }
                if(parseFloat(closing_credit) > parseFloat(closing_debit)){
                    results.closing_credit = parseFloat(closing_credit) - parseFloat(closing_debit);
                }

                return results;
            },

            getTotals(){
                const totals = {
                    opening_debit: 0,
                    opening_credit: 0,
                    transactions_debit: 0,
                    transactions_credit: 0,
                    closing_debit: 0,
                    closing_credit: 0
                }
                totals.opening_debit = this.accounts.reduce((total, account) => total + parseFloat(this.getAccountData(account.id).opening_debit), 0);
                totals.opening_credit = this.accounts.reduce((total, account) => total + parseFloat(this.getAccountData(account.id).opening_credit), 0);
                totals.transactions_debit = this.accounts.reduce((total, account) => total + parseFloat(this.getAccountData(account.id).transactions_debit), 0);
                totals.transactions_credit = this.accounts.reduce((total, account) => total + parseFloat(this.getAccountData(account.id).transactions_credit), 0);
                totals.closing_debit = this.accounts.reduce((total, account) => total + parseFloat(this.getAccountData(account.id).closing_debit), 0);
                totals.closing_credit = this.accounts.reduce((total, account) => total + parseFloat(this.getAccountData(account.id).closing_credit), 0);
                return totals;
            },


            fetchData() {
                this.isloading = true;
                axios.get('/accounts/reports/trial_balance', {
                        params: {
                            start_date: this.start_date,
                            end_date: this.end_date,
                        }
                    })
                    .then((response) => {
                        this.accounts = response.data.accounts;
                        this.opening_voucher_details = response.data.opening_voucher_details
                        this.transactions_voucher_details = response.data.transactions_voucher_details

                        this.isloading = false;
                    })
            }
        }
    }
</script>
