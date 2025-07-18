<x-app-layout>
    <x-slot name="title">
        {{ __('messages.cancel_surveys') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.cancel_surveys') }}
                <span id="counter"></span>
            </h2>
        </div>
    </x-slot>

    <div
        x-data="cancelSurveysComponent()"
    >


        <template x-teleport="#counter">
            <span 
                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                x-text="totalRecords"
            >
            </span>
        </template>

        <!-- Filters -->
        <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            <div>
                <x-label for="order_id">{{ __('messages.order_number') }}</x-label>
                <x-input id="order_id" x-model.debounce="filters.order_id" class="w-full text-start py-0" />
            </div>
            <div>
                <x-label for="reason">{{ __('messages.reason') }}</x-label>
                <!-- start area searchable select -->
                <div 
                    x-data="{
                        items:reasons,
                        selectedItemIds:filters.reasons,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.reasons"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end area searchable select -->
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
                    <x-th>{{__('messages.order_number')}}</x-th>
                    <x-th>{{__('messages.cancel_reason')}}</x-th>
                    <x-th>{{__('messages.customer_name')}}</x-th>
                    <x-th>{{__('messages.customer_phone')}}</x-th>
                    <x-th>{{__('messages.created_at')}}</x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="survey in surveys" :key="survey.id">
                    <x-tr>
                        <x-th x-text="survey.order_id"></x-th>
                        <x-td>
                            <div x-text="survey.translated_cancel_reason"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="survey.other_reason"></div>
                        </x-td>
                        <x-td x-text="survey.order.customer.name"></x-td>
                        <x-td x-text="survey.order.phone.number"></x-td>
                        <x-td>
                            <div class="flex flex-col gap-1">
                                <div x-text="survey.formatted_created_at_date"></div>
                                <div x-text="survey.formatted_created_at_time"></div>
                            </div>
                        </x-td>
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
        function cancelSurveysComponent() {
            return {
                loading: false,
                reasons: @js($reasons),
                surveys: [],
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                filters: {
                    order_id: '',
                    reasons: [],
                    start_created_at: '',
                    end_created_at: '',
                },

                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.$watch('filters', () => {
                        this.getSurveys(1);
                    });
                },


                getSurveys(page=1) {
                    this.loading = true;
                    axios.get('/cancel-surveys?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.surveys = [];
                                this.surveys = response.data.data;
                            } else {
                                this.surveys = [...this.surveys, ...response.data.data];
                            }
                            this.currentPage = response.data.meta.current_page;
                            this.lastPage = response.data.meta.last_page;
                            this.totalRecords = response.data.meta.total;
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
                    this.getSurveys(this.currentPage);
                },
            }
        }
    </script>
</x-app-layout>
