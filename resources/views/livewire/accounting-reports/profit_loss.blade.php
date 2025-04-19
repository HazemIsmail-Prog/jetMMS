<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.profit_loss_report') }}
        </h2>
    </x-slot>

    <div x-data="profitLossComponent">
        <div class="flex items-end gap-3">
            <div>
                <x-label for="start_date">{{ __('messages.start_date') }}</x-label>
                <x-input type="date" id="start_date" x-model="filters.start_date" />
            </div>
            <div>
                <x-label for="end_date">{{ __('messages.end_date') }}</x-label>
                <x-input type="date" id="end_date" x-model="filters.end_date" />
            </div>
            <div>
                <x-button class="justify-self-end" x-on:click="getData">
                    {{ __('messages.refresh') }}
                </x-button>
            </div>
        </div>

        <!-- full screen overlay with blur background loading spinner -->
        <div x-show="isLoading" class="fixed inset-0 flex justify-center items-center h-screen bg-black bg-opacity-50">
            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500"></div>
        </div>

        <template x-for="table in table_data" :key="table.title">
            <div x-show="!isLoading" class="mt-4 overflow-x-auto">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th colspan="2" x-text="table.title"></x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                        <template x-for="account in table.data" :key="account.id">
                            <x-tr x-show="account.total > 0">
                                <x-td x-text="account.name"></x-td>
                                <x-td class="text-right !w-[200px] !text-xs" x-text="roundAndFormatNumber(account.total)"></x-td>
                            </x-tr>
                        </template>
                    </tbody>
                    <x-tfoot>
                        <tr>
                            <x-th class="text-right !font-bold">{{ __('messages.total') }}</x-th>
                            <x-th class="text-right !w-[200px] !font-bold" x-text="roundAndFormatNumber(table.total)"></x-th>
                        </tr>
                    </x-tfoot>
                </x-table>
            </div>
        </template>

        <div x-show="!isLoading" class="mt-4">
            <!-- summary table -->
            <x-table>
                <x-thead>
                    <tr>
                        <x-th colspan="2">{{ __('messages.summary') }}</x-th>
                    </tr>
                </x-thead>
                <tbody>
                    <x-tr>
                        <x-td>{{ __('messages.total_incomes') }}</x-td>
                        <x-td class="text-right !w-[200px] !text-xs" x-text="roundAndFormatNumber(table_data[0]?.total ?? 0)"></x-td>
                    </x-tr>
                    <x-tr>
                        <x-td>{{ __('messages.total_costs') }}</x-td>
                        <x-td class="text-right !w-[200px] !text-xs" x-text="roundAndFormatNumber(table_data[1]?.total ?? 0)"></x-td>
                    </x-tr>
                    <x-tr>
                        <x-td>{{ __('messages.total_expenses') }}</x-td>
                        <x-td class="text-right !w-[200px] !text-xs" x-text="roundAndFormatNumber(table_data[2]?.total ?? 0)"></x-td>
                    </x-tr>
                </tbody>
                <x-tfoot>
                    <tr>
                        <x-th>{{ __('messages.profit') }}</x-th>
                        <x-th class="text-right !w-[200px]" x-text="roundAndFormatNumber(profitLoss().profit)"></x-th>
                    </tr>
                    <tr>
                        <x-th>{{ __('messages.loss') }}</x-th>
                        <x-th class="text-right !w-[200px]" x-text="roundAndFormatNumber(profitLoss().loss)"></x-th>
                    </tr>
                </x-tfoot>
            </x-table>
        </div>

    </div>

    <script>
        function profitLossComponent() {
            return {
                table_data: [],
                income_data: [],
                cost_data: [],
                expense_data: [],
                isLoading: false,
                filters: {
                    start_date: new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0],
                    end_date: new Date().toISOString().split('T')[0],
                },
                init() {
                    this.getData();
                },
                getData() {
                    this.isLoading = true;
                    axios.get('/accounts/reports/profit_loss', {
                        params: this.filters
                    })
                        .then(response => {
                            this.income_data = response.data.income_data;
                            this.cost_data = response.data.cost_data;
                            this.expense_data = response.data.expense_data;
                            this.table_data = [
                                {
                                    title: '{{ __('messages.incomes') }}',
                                    data: this.income_data,
                                    total: Object.values(this.income_data).reduce((acc, curr) => acc + curr.total, 0)
                                },
                                {
                                    title: '{{ __('messages.costs') }}',
                                    data: this.cost_data,
                                    total: Object.values(this.cost_data).reduce((acc, curr) => acc + curr.total, 0)
                                },
                                {
                                    title: '{{ __('messages.expenses') }}',
                                    data: this.expense_data,
                                    total: Object.values(this.expense_data).reduce((acc, curr) => acc + curr.total, 0)
                                }
                            ];
                        }).finally(() => {
                            this.isLoading = false;
                        });
                },

                roundAndFormatNumber(value) {
                    return value > 0 ? value.toLocaleString('en-US', { minimumFractionDigits: 3, maximumFractionDigits: 3 }) : 0;
                },

                profitLoss() {
                    let value = this.table_data[0].total - this.table_data[1].total - this.table_data[2].total;
                    return {
                        'profit': value > 0 ? value : 0,
                        'loss': value < 0 ? value : 0
                    }
                }
            }
        }
    </script>

</x-app-layout>
