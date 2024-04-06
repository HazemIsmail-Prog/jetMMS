@props(['title', 'total' => null, 'technician' => null,'id'])

<div 
    class="flex items-center justify-between rounded-md cursor-pointer select-none flex-shrink-0 h-10 p-4 bg-gray-300 dark:bg-gray-700">
    <div @click="hiddenContainers.push({ id: '{{ $id }}', title: '{{ $title }}' });" class="flex-1 text-sm font-semibold uppercase truncate">{{ $title }}</div>
    <div class="hidden lg:flex">
        @if ($technician)
            @if ($technician->todays_completed_orders_count > 0)
                <a target="__blank" href="{{ route('order.index', [
                    'filters[technicians]' => $technician->id,
                    'filters[statuses]' => App\Models\Status::COMPLETED,
                    'filters[start_completed_at]' => today()->format('Y-m-d'),
                    'filters[end_completed_at]' => today()->format('Y-m-d'),
                ]) }}"
                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-700 dark:text-green-300">
                    {{ $technician->todays_completed_orders_count }}
                </a>
            @endif
        @endif
        @if ($total)
            <div @click="hiddenContainers.push({ id: '{{ $id }}', title: '{{ $title }}' });"
                class="bg-gray-100 text-gray-800 border border-gray-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500">
                {{ $total }}
            </div>
        @endif
    </div>
</div>
