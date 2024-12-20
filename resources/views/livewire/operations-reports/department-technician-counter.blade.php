<div x-data="departmentTechnicianCounter()" class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.department_technician_statistics') }}
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

    <div x-show="!isLoading" class=" space-y-2">
        <template x-for="department in departments" :key="department.id">
            <div x-show="department.total_orders_count > 0"
                class=" cursor-pointer text-center p-3 border dark:border-gray-700 rounded-lg space-y-2"
                x-data={show:false} @click="show=!show">
                <h2 class="font-semibold text-center text-gray-800 dark:text-gray-200 leading-tight"
                    x-text="department.name">
                </h2>

                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 flex">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                        x-bind:style="`width: ${ (department.completed_orders_count / department.total_orders_count) * 100 }%`"
                        x-text="formattedNumber(department.completed_orders_count)">
                    </div>
                    <div class=" text-xs font-medium dark:text-white text-center p-0.5 leading-none flex-grow"
                        x-text="formattedNumber(department.total_orders_count - department.completed_orders_count)">
                    </div>
                </div>

                <h2 class="font-semibold text-center text-sm text-gray-800 dark:text-gray-200 leading-tight">
                    <span x-text="formattedNumber(department.completed_orders_count)"></span> /
                    <span x-text="formattedNumber(department.total_orders_count)"></span>
                    (<span
                        x-text="`${((department.completed_orders_count / department.total_orders_count) * 100).toFixed(2)}%`"></span>)
                </h2>

                <div x-show="show">
                    <x-table>
                        <template
                            x-for="technician in department.technicians.sort((a, b) => b.completed_orders_count - a.completed_orders_count)"
                            :key="technician.id">
                            <template x-if="technician.completed_orders_count > 0">
                                <x-tr class=" text-center">
                                    <x-td x-text="technician.name"></x-td>
                                    <x-td width="15%" x-text="technician.completed_orders_count"></x-td>
                                </x-tr>
                            </template>
                        </template>
                    </x-table>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
    function departmentTechnicianCounter() {
        return {
            isLoading: false,
            selectedDate: new Date().getMonth() + 1 + '-' + new Date().getFullYear(),
            dateFilter: [],
            departments: [],
            init() {
                this.fetchData();
            },
            fetchData() {
                this.isLoading = true;
                axios.get('/departmentTechnicianCounter', {
                    params: {
                        selectedDate: this.selectedDate
                    }
                }).then(response => {
                    this.departments = response.data.departments;
                    this.dateFilter = response.data.dateFilter;
                }).catch(error => {
                    console.log(error);
                }).finally(() => {
                    this.isLoading = false;
                });
            },
            formattedNumber(number) {
                if (number === 0 || !number) {
                    return '-';
                }
                return number.toLocaleString('en-US');
            },
        }
    }
</script>
