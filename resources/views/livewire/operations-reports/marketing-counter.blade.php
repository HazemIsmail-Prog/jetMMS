<div x-data="marketingCounter()" class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.marketing_counter') }}
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
                <x-th class=" !px-0.5">{{ __('messages.date') }}</x-th>
                <template x-for="type in types" :key="type.id">
                    <x-th class=" !px-0.5 !text-center" width="7%" x-text="type.name"></x-th>
                </template>
                <x-th class=" !px-0.5 !text-center" width="7%">{{ __('messages.total') }}</x-th>
            </tr>
        </x-thead>
        <tbody class=" text-sm" wire:poll.30s>
            <template x-for="(row,date) in counters" :key="date">
                <x-tr>
                    <x-td class=" !px-0.5">
                        <div class=" whitespace-nowrap" x-text="weekDay(date)"></div>
                        <div class=" whitespace-nowrap" x-text="date"></div>
                    </x-td>
                    <template x-for="type in types" :key="type.id">
                        <x-td class=" !px-0.5 !text-center" x-text="formattedNumber(row[type.id])"></x-td>
                    </template>
                    <x-td class=" !px-0.5 !text-center" x-text="formattedNumber(rowTotal(row))"></x-td>
                </x-tr>
            </template>
        </tbody>
        <x-tfoot class="sticky bottom-0">
            <tr>
                <x-th>{{ __('messages.total') }}</x-th>
                <template x-for="type in types" :key="type.id">
                    <x-th>
                        <div class="text-center" x-text="formattedNumber(columnTotal(type.id))"></div>
                    </x-th>
                </template>
                <x-th>
                    <div class="text-center" x-text="formattedNumber(grandTotal())"></div>
                </x-th>
            </tr>
        </x-tfoot>
    </x-table>

</div>

<script>
    function marketingCounter() {
        return {
            isLoading: false,
            types: [],
            selectedDate: new Date().getMonth() + 1 + '-' + new Date().getFullYear(),
            dateFilter: [],
            counters: [],

            init() {
                this.fetchData();
            },

            rowTotal(row) {
                return Object.values(row).reduce((a, b) => a + b, 0);
            },

            grandTotal() {
                return Object.values(this.counters).reduce((a, b) => a + this.rowTotal(b), 0);
            },

            formattedNumber(number) {
                if (number === 0 || !number) {
                    return '-';
                }
                return number.toLocaleString('en-US');
            },

            columnTotal(typeId) {
                return Object.values(this.counters).reduce((a, b) => a + (b[typeId] || 0), 0);
            },

            weekDay(date) {
                const [day, month, year] = date.split('-');
                return new Date(year, month - 1, day).toLocaleDateString('ar-EG', {
                    weekday: 'long'
                });
            },

            fetchData() {
                this.isLoading = true;
                axios.get('/marketingCounter', {
                    params: {
                        selectedDate: this.selectedDate
                    }
                }).then(response => {
                    this.dateFilter = response.data.dateFilter;
                    this.types = response.data.types;
                    this.counters = response.data.counters;
                }).finally(() => {
                    this.isLoading = false;
                });
            },
        }
    }
</script>
