<div x-data="technicianCompletionAverage()" class=" border dark:border-gray-700 rounded-lg p-4 h-[370px] flex flex-col">
    <div class=" flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.average_completed_orders_for_technicians') }}
            </h2>
        </div>

        <!-- Filter Dropdown -->
        <div class="relative">
            <x-dropdown width="48">
                <x-slot name="trigger">
                    <x-badgeWithCounter>
                        <span x-text="selectedYear" class="text-sm px-2 py-1"></span>
                    </x-badgeWithCounter>
                </x-slot>
                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('messages.year') }}
                    </div>
                    <template x-for="year in years" :key="year">
                        <div x-bind:selected="year == selectedYear" x-text="year"
                            class="block w-full px-4 py-2 text-start cursor-pointer text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                            x-on:click="changeYear(year)"></div>
                    </template>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
    <x-section-border />
    <div x-show="isloading" class="h-full flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-10 h-10 animate-spin">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
    </div>
    <canvas x-show="!isloading" class="h-full flex items-center justify-center overflow-hidden"
        id="technicianCompletionAverageChart"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function technicianCompletionAverage() {
        return {
            isloading: false,
            ordersCountLabel: '',
            years: [],
            selectedYear: new Date().getFullYear(),
            ordersCount: [],
            totalTechnicians: [],
            average: [],
            labels: [],
            chartInstance: null,

            init() {
                this.getOrders();
            },

            changeYear(year) {
                if (this.selectedYear === year) return;
                this.selectedYear = year;
                this.getOrders();
            },

            getOrders() {
                this.isloading = true;
                axios.get(`/technicianCompletionAverage/${this.selectedYear}`)
                    .then(response => {
                        this.years = response.data.years;
                        this.labels = response.data.labels;
                        this.ordersCount = response.data.ordersCount;
                        this.totalTechnicians = response.data.totalTechnicians;
                        this.average = response.data.average;
                        this.ordersCountLabel = response.data.ordersCountLabel;
                        this.drawChart();
                    })
                    .catch(error => {
                        console.log(error);
                    }).finally(() => {
                        this.isloading = false;
                    });
            },

            drawChart() {
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                const ctx = document.getElementById('technicianCompletionAverageChart').getContext('2d');
                const alpineInstance = this; // Preserve the Alpine.js instance

                this.chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.labels,
                        datasets: [{
                            label: this.ordersCountLabel,
                            data: this.ordersCount,
                            backgroundColor: 'rgba(46, 184, 92, 0.2)',
                            borderColor: 'rgba(46, 184, 92, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Disable
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        // Use the Alpine.js instance to get data
                                        const index = tooltipItem.dataIndex;
                                        const totalOrders = alpineInstance.ordersCount[index] || 0;
                                        const totalTechnicians = alpineInstance.totalTechnicians[
                                                index] ||
                                            0;
                                        const average = alpineInstance.average[index] || 0;

                                        return [
                                            `${@json(__('messages.completed_orders'))}: ${totalOrders}`,
                                            `${@json(__('messages.total_technicians'))}: ${totalTechnicians}`,
                                            `${@json(__('messages.average_completed_orders_for_technicians'))}: ${average}`
                                        ];
                                    }
                                }
                            }
                        }
                    }
                });
            }

        }

    }
</script>