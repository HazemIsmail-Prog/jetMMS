<div class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.customers') }}
            </h2>
        </div>

        <!-- Filter Dropdown -->
        {{-- <div class="relative">
            <x-dropdown width="48">
                <x-slot name="trigger">
                    <x-badgeWithCounter>
                        <x-svgs.ellipsis-horizontal class="h-6" />
                    </x-badgeWithCounter>
                </x-slot>
                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('messages.year') }}
                    </div>
                    @foreach ($years as $year)
                        <div wire:click="changeYear({{ $year }})"
                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                            {{ $year }}
                        </div>
                    @endforeach
                </x-slot>
            </x-dropdown>
        </div> --}}
    </div>
    <x-section-border />
    <canvas id="customerChart"></canvas>
</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
    <script>
        $wire.on('customersFetched', () => {
            setTimeout(function() {
                var ctx = document.getElementById('customerChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json(
                            $customers->map(function ($customer) {
                                return $customer->month . '-' . $customer->year;
                            })),
                        datasets: [{
                            label: @json($title),
                            data: @json($customers->pluck('total')),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 4
                        }, ]
                    },
                });
            }, 10)
        });
    </script>
@endscript
