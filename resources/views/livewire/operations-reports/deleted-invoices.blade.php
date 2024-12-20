<div x-data="deletedInvoices()" class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.deleted_invoices_counter') }}
        </h2>
        <x-select x-show="dateFilter.length" x-on:change="fetchData()" x-model="selectedDate" class=" px-2 py-1">
            <template x-for="date in dateFilter" :key="`${date.month}-${date.year}`">
                <option x-text="`${date.month}-${date.year}`" x-bind:value="`${date.month}-${date.year}`"
                    x-bind:selected="selectedDate == `${date.month}-${date.year}`"></option>
            </template>
        </x-select>
    </div>

    <x-section-border />

    <div x-show="isLoading" class="h-[500px] flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-10 h-10 animate-spin">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
    </div>

    <x-table x-show="!isLoading">
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th class=" !text-center" width="15%">{{ __('messages.total') }}</x-th>
            </tr>
        </x-thead>
        <tbody>
            <template x-for="user in users" :key="user.id">
                <x-tr>
                    <x-td x-text="user.name"></x-td>
                    <x-td class=" !text-center" x-text="user.deleted_invoices_count"></x-td>
                </x-tr>
            </template>
        </tbody>
        <x-tfoot>
            <tr>
                <x-th>{{ __('messages.total') }}</x-th>
                <x-th class=" !text-center"
                    x-text="users.length ? users.map(user => user.deleted_invoices_count).reduce((a, b) => a + b) : '-'"></x-th>
            </tr>
        </x-tfoot>
    </x-table>

</div>

<script>
    function deletedInvoices() {
        return {
            isLoading: false,
            selectedDate: new Date().getMonth() + 1 + '-' + new Date().getFullYear(),
            dateFilter: [],
            users: [],

            init() {
                this.fetchData();
            },

            fetchData() {
                this.isLoading = true;
                axios.get('/deletedInvoices', {
                    params: {
                        selectedDate: this.selectedDate
                    }
                }).then(response => {
                    this.users = response.data.users;
                    this.dateFilter = response.data.dateFilter;
                }).catch(error => {
                    console.log(error);
                }).finally(() => {
                    this.isLoading = false;
                });
            },
        }
    }
</script>
