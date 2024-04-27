<div class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.monthly_orders_statistics') }}
        </h2>
        <x-select wire:model.live="selectedDate">
            @foreach ($this->dateFilter as $date)
            <option value="{{str_pad($date->month, 2, '0', STR_PAD_LEFT)}}-{{$date->year}}">{{str_pad($date->month, 2,
                '0', STR_PAD_LEFT)}}-{{$date->year}}</option>
            @endforeach
        </x-select>
    </div>


    <x-section-border />

    <x-table>
        <x-thead>
            <tr>
                <x-th class=" !px-0.5">{{ __('messages.date') }}</x-th>
                @foreach ($this->statuses as $status)
                <x-th class=" !px-0.5 !text-center" width="7%">{{ $status->name }}</x-th>
                @endforeach
                <x-th class=" !px-0.5 !text-center" width="7%">{{ __('messages.total') }}</x-th>
            </tr>
        </x-thead>
        <tbody class=" text-sm" wire:poll.30s>
            @forelse ($this->counters->groupBy('date') as $row)
            <x-tr>
                <x-td class="!px-0.5">
                    <div class=" whitespace-nowrap">{{ __('messages.' . date('l', strtotime($row[0]->date))) }}
                    </div>
                    <div class=" whitespace-nowrap">{{ date('d-m-Y', strtotime($row[0]->date)) }}</div>
                </x-td>
                @foreach ($this->statuses as $status)
                <x-td class="!px-0.5 !text-center">
                    {{ $row->where('status_id', $status->id)->pluck('count')->first() > 0?
                    number_format($row->where('status_id', $status->id)->pluck('count')->first()): '-' }}
                </x-td>
                @endforeach
                <x-td class="!px-0.5 !text-center">
                    {{ $row->sum('count') > 0 ? number_format($row->sum('count')) : '-' }}
                </x-td>
            </x-tr>
            @empty
            <x-tr>
                <td colspan="9" class=" text-center">{{ __('messages.no_orders') }}</td>
            </x-tr>
            @endforelse
        </tbody>
        <x-tfoot class="sticky bottom-0">
            <tr>
                <x-th>{{ __('messages.total') }}</x-th>
                @foreach ($this->statuses as $status)
                <x-th>
                    {{ $this->counters->where('status_id', $status->id)->sum('count') > 0 ?
                    number_format($this->counters->where('status_id', $status->id)->sum('count')) : '-' }}
                </x-th>
                @endforeach
                <x-th>
                    {{ $this->counters->sum('count') > 0 ? number_format($this->counters->sum('count')) : '-' }}
                </x-th>
            </tr>
        </x-tfoot>
    </x-table>

</div>