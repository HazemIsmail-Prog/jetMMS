<div x-data="dailyStatistics()" class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.daily_statistics') }}
        </h2>
    </div>

    <x-section-border />

    <div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

        <div class="border border-green-600 dark:border-green-700 rounded-lg p-4">
            <h2 class="font-semibold text-lg flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.todays_completed_orders_count') }}
            </h2>
            <h2 class="font-semibold text-xl justify-end flex gap-3 items-center text-green-600 dark:text-green-700 leading-tight" x-text="completedOrdersCount"></h2>
        </div>

        <div class="border border-red-600 dark:border-red-700 rounded-lg p-4">
            <h2 class="font-semibold text-lg flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.todays_cancelled_orders_count') }}
            </h2>
            <h2 class="font-semibold text-xl justify-end flex gap-3 items-center text-red-600 dark:text-red-700 leading-tight" x-text="cancelledOrdersCount"></h2>
        </div>

        <div class="border border-teal-600 dark:border-teal-700 rounded-lg p-4">
            <h2 class="font-semibold text-lg flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.todays_customers_count') }}
            </h2>
            <h2 class="font-semibold text-xl justify-end flex gap-3 items-center text-teal-600 dark:text-teal-700 leading-tight" x-text="customersCount"></h2>
        </div>

    </div>

</div>

<script>
    function dailyStatistics(){
        return{
            completedOrdersCount: 0,
            cancelledOrdersCount: 0,
            customersCount: 0,

            init(){
                this.loadDailyStatistics();
            },

            loadDailyStatistics(){
                axios.get('/dailyStatistics')
                    .then(response => {                        
                        this.completedOrdersCount = response.data.completedOrdersCount;
                        this.cancelledOrdersCount = response.data.cancelledOrdersCount;
                        this.customersCount = response.data.customersCount;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>