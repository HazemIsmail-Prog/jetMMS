<div class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.department_technician_statistics') }}
        </h2>
        <x-select wire:model.live="selectedDate">
            @foreach ($this->dateFilter as $date)
            <option value="{{str_pad($date->month, 2, '0', STR_PAD_LEFT)}}-{{$date->year}}">{{str_pad($date->month, 2,
                '0', STR_PAD_LEFT)}}-{{$date->year}}</option>
            @endforeach
        </x-select>
    </div>

    <x-section-border />

    <div wire:poll.30s class=" space-y-2">
        @foreach ($this->departments as $department)
        @if ($department->total_orders_count > 0)
        <div 
            class=" cursor-pointer text-center p-3 border dark:border-gray-700 rounded-lg space-y-2" 
            x-data={show:false}
            @click="show=!show"
        >
         
            <h2 class="font-semibold text-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ $department->name }}
            </h2>
            
            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 flex">
                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                    style="width: {{ ($department->completed_orders_count / $department->total_orders_count) * 100 }}%">
                    {{ $department->completed_orders_count }}
                </div>
                <div class=" text-xs font-medium dark:text-white text-center p-0.5 leading-none flex-grow">
                    {{ $department->total_orders_count - $department->completed_orders_count }}
                </div>
            </div>
            
            <h2 class="font-semibold text-center text-sm text-gray-800 dark:text-gray-200 leading-tight">
                {{ $department->completed_orders_count }} /
                {{ $department->total_orders_count }}
                ({{ number_format(($department->completed_orders_count /
                $department->total_orders_count) * 100, 2) }}%)
            </h2>

            <div x-show="show">
                <x-table>
                    @foreach ($department->technicians->sortByDesc('completed_orders_count') as $technician)
                    @if ($technician->completed_orders_count > 0)
                    <x-tr class=" text-center">
                        <x-td>{{ $technician->name }}</x-td>
                        <x-td width="15%">{{ $technician->completed_orders_count }}</x-td>
                    </x-tr>
                    @endif
                    @endforeach
                </x-table>
            </div>
        </div>
        @endif
        @endforeach


    </div>
</div>