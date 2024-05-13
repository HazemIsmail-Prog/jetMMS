<div class=" border dark:border-gray-700 rounded-lg p-4">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.deleted_invoices_counter') }}
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
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th class=" !text-center" width="15%">{{ __('messages.total') }}</x-th>
            </tr>
        </x-thead>
        <tbody wire:poll.30s>
            @forelse ($this->users->sortBy('name')->sortByDesc('deleted_invoices_count') as $user)
            <x-tr>
                <x-td>{{ $user->name }}</x-td>
                <x-td class=" !text-center">{{ $user->deleted_invoices_count }}</x-td>
            </x-tr>
            @empty
            <x-tr>
                <td colspan="2" class=" text-center">{{ __('messages.no_deleted_invoices') }}</td>
            </x-tr>
            @endforelse
        </tbody>
        <x-tfoot>
            <tr>
                <x-th>{{ __('messages.total') }}</x-th>
                <x-th class=" !text-center">
                    {{ $this->users->sum('deleted_invoices_count') > 0 ? number_format($this->users->sum('deleted_invoices_count')) : '-' }}
                </x-th>
            </tr>
        </x-tfoot>
    </x-table>

</div>