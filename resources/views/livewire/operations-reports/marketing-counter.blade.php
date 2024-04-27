<div class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.marketing_counter') }}
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
                <x-th>{{ __('messages.date') }}</x-th>
                @foreach ($this->types as $type)
                <x-th class=" !text-center" width="15%">{{ __('messages.'. $type) }}</x-th>
                @endforeach
                <x-th class=" !text-center" width="15%">{{ __('messages.total') }}</x-th>
            </tr>
        </x-thead>
        <tbody wire:poll.30s>
            @forelse ($this->marketings->groupBy('date') as $row)
            <x-tr>
                <x-td>
                    <div>{{ __('messages.' . date('l', strtotime($row[0]->date))) }}</div>
                    <div>{{ date('d-m-Y', strtotime($row[0]->date)) }}</div>
                </x-td>
                @foreach ($this->types as $type)
                <x-td class=" !text-center">
                    {{ $row->where('type', $type)->pluck('count')->first() > 0? number_format($row->where('type',
                    $type)->pluck('count')->first()): '-' }}
                </x-td>
                @endforeach
                <x-td class=" !text-center">
                    {{ $row->sum('count') > 0 ? number_format($row->sum('count')) : '-' }}
                </x-td>
            </x-tr>
            @empty
            <x-tr>
                <td colspan="9">{{ __('messages.no_marketings') }}</td>
            </x-tr>
            @endforelse
        </tbody>
        <x-tfoot>
            <tr>
                <x-th>{{ __('messages.total') }}</x-th>
                @foreach ($this->types as $type)
                <x-th class=" !text-center">
                    {{ $this->marketings->where('type', $type)->sum('count') > 0 ?
                    number_format($this->marketings->where('type',
                    $type)->sum('count')) : '-' }}
                </x-th>
                @endforeach
                <x-th class=" !text-center">
                    {{ $this->marketings->sum('count') > 0 ? number_format($this->marketings->sum('count')) : '-' }}
                </x-th>
            </tr>
        </x-tfoot>
    </x-table>

</div>