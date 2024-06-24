<div class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.average_completed_orders_for_technicians') }}
            </h2>
        </div>
    </div>
    <x-section-border />
    <canvas id="averageCompletedOrdersForTechniciansChart"></canvas>

</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script


<script>
    $wire.on('ordersFetched', () => {
        setTimeout(function() {
            var ctx = document.getElementById('averageCompletedOrdersForTechniciansChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(
                        $completed_orders->map(function ($order) {
                            return $order->month . '-' . $order->year;
                        })
                    ),
                    datasets: [
                        {
                            label: @json($completed_title),
                            data: @json($completed_orders->pluck('average')),
                            backgroundColor: 'rgba(46, 184, 92, 0.2)',
                            borderColor: 'rgba(46, 184, 92, 1)',
                            borderWidth: 4
                        }
                    ]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    var index = tooltipItem.dataIndex;
                                    var order = @json($completed_orders)[index];
                                    var total = order.total;
                                    var totalTechnicians = order.total_technicians;
                                    var average = order.average;

                                    return [
                                        @json(__('messages.completed_orders')) + ': ' + total,
                                        @json(__('messages.total_technicians')) + ': ' + totalTechnicians,
                                        @json(__('messages.average_completed_orders_for_technicians')) + ': ' + average
                                    ];
                                }
                            }
                        }
                    }
                }
            });
        }, 10);
    });
</script>

@endscript