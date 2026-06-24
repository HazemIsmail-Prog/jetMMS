<x-app-layout>
    <x-slot name="title">
        {{ __('messages.dispatching_history') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.dispatching_history') }}
                <span id="counter"></span>
            </h2>
            <!-- <span id="excel"></span> -->
        </div>
    </x-slot>

    <div
        x-data="invoicesComponent()"
    >

        <template x-teleport="#counter">
            <span 
                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                x-text="totalRecords"
            >
            </span>
        </template>

        <!-- <template x-teleport="#excel">
            <x-button 
                @click="exportToExcel"
                x-bind:disabled="exporting || totalRecords > maxExportSize"
                x-bind:class="{
                    'animate-pulse duration-75 cursor-not-allowed': exporting,
                    'cursor-not-allowed': totalRecords > maxExportSize
                }"
            >
                {{ __('messages.export_to_excel') }}
            </x-button>
        </template> -->

        <!-- Filters -->
        <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            <div>
                <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
                <x-input type="number" id="order_number" x-model.debounce="filters.order_number" class="w-full py-0" dir="ltr" />
            </div>
            <div>
                <x-label for="department">{{ __('messages.department') }}</x-label>
                <!-- start searchable select -->
                <div 
                    x-data="{
                        items:departments,
                        selectedItemIds:filters.department_ids,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.department_ids"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end searchable select -->
            </div>

            <div>
                <x-label for="technician">{{ __('messages.technician') }}</x-label>
                <!-- start searchable select -->
                <div 
                    x-data="{
                        items:technicians,
                        selectedItemIds:filters.technician_ids,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.technician_ids"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end searchable select -->
            </div>

            <div>
                <x-label for="creator">{{ __('messages.creator') }}</x-label>
                <!-- start searchable select -->
                <div 
                    x-data="{
                        items:users,
                        selectedItemIds:filters.user_ids,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.user_ids"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end searchable select -->
            </div>

            <div>
                <x-label for="status">{{ __('messages.status') }}</x-label>
                <!-- start searchable select -->
                <div 
                    x-data="{
                        items:statuses,
                        selectedItemIds:filters.status_ids,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.status_ids"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end searchable select -->
            </div>

            <div>
                <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
                <x-input id="start_created_at" class="w-36 min-w-full text-center py-0" type="date"
                    x-model="filters.start_created_at" />
                <x-input id="end_created_at" class="w-36 min-w-full text-center py-0" type="date"
                    x-model="filters.end_created_at" />
            </div>
        </div>

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.order_number') }}</x-th>
                    <x-th>{{ __('messages.created_at') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th>{{ __('messages.creator') }}</x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="order_status in order_statuses" :key="order_status.id">
                    <x-tr>
                        <x-td x-text="order_status.order_id"></x-td>
                        <x-td>
                            <div class="flex flex-col">
                                <span x-text="order_status.formated_created_at_date"></span>
                                <span class="text-xs" x-text="order_status.formated_created_at_time"></span>
                            </div>
                        </x-td>
                        <x-td x-text="getDepartmentName(order_status.order.department_id)"></x-td>
                        <x-td x-text="getTechnicianName(order_status.order.technician_id)"></x-td>
                        <x-td>
                            <span :style="{ color: order_status.status.color }" x-text="order_status.status.name"></span>
                        </x-td>
                        <x-td x-text="getCreatorName(order_status.user_id)"></x-td>
                    </x-tr>
                </template>
            </tbody>

        </x-table>

        <!-- load more letters -->
        <div class="flex justify-center mt-4" x-show="currentPage < lastPage">
            <x-button @click="loadMore">{{__('messages.load_more')}}</x-button>
        </div>
    </div>


    <script>
        function invoicesComponent() {
            return {
                departments: @js($departments),
                technicians: @js($technicians),
                users: @js($users),
                statuses: @js($statuses),
                loading: false,
                exporting: false,
                maxExportSize: 5000,
                order_statuses: [],
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                filters: {
                    order_number: '',
                    department_ids: [],
                    technician_ids: [],
                    user_ids: [],
                    status_ids: [],
                    start_created_at: null,
                    end_created_at: null, 
                },

                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.$watch('filters', () => {
                        this.getOrderStatuses(1);
                    });
                    this.initListeners();
                },

                getDepartmentName(id) {
                    return this.departments.find(department => department.id === id)?.name;
                },

                getTechnicianName(id) {
                    return this.technicians.find(technician => technician.id === id)?.name;
                },

                getCreatorName(id) {
                    return this.users.find(user => user.id === id)?.name;
                },

                addTechnicianToFilters(id) {
                    if(this.filters.technician_ids.includes(id)) return;
                    this.filters.technician_ids = [id];
                },

                addDepartmentToFilters(id) {
                    if(this.filters.department_ids.includes(id)) return;
                    this.filters.department_ids = [id];
                },

                getOrderStatuses(page=1) {
                    this.loading = true;
                    axios.get('/dispatching-history?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.order_statuses = [];
                                this.order_statuses = response.data.data;
                            } else {
                                // remove duplicates before adding new invoices
                                this.order_statuses = [...this.order_statuses, ...response.data.data.filter(order_status => !this.order_statuses.some(o => o.id === order_status.id))];
                            }
                            this.currentPage = response.data.current_page;
                            this.lastPage = response.data.last_page;
                            this.totalRecords = response.data.total;
                        })

                        .catch(error => {
                            alert(error.response.data.message);
                        })                        
                        .finally(() => {
                            this.loading = false;
                        });
                },

                loadMore() {
                    if (this.currentPage == this.lastPage || this.loading) return;                    
                    this.currentPage = (this.currentPage || 1) + 1;
                    this.getOrderStatuses(this.currentPage);
                },

                initListeners() {

                },

                checkFilters() {
                    return Object.values(this.filters).every(value => value === '');
                },

                // exportToExcel() {
                //     if(this.totalRecords > this.maxExportSize) {
                //         alert('You can only export up to 5000 records');
                //         return;
                //     }
                //     this.exporting = true;
                //     axios.get('/invoices/exportToExcel', {
                //         params: this.filters,
                //         responseType: 'blob'
                //     })
                //     .then(response => {
                //         const url = window.URL.createObjectURL(new Blob([response.data]));
                //         const link = document.createElement('a');
                //         link.href = url;
                //         link.setAttribute('download', 'Invoices.xlsx');
                //         document.body.appendChild(link);
                //         link.click();
                //         link.remove();
                //         window.URL.revokeObjectURL(url);
                //     })
                //     .catch(error => {
                //         alert('Error downloading file: ' + error.message);
                //     })
                //     .finally(() => {
                //         this.exporting = false;
                //     });
                // },


            }
        }
    </script>
</x-app-layout>
