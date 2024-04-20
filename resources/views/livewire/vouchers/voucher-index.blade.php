<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ $title }}
                <span id="counter"></span>
            </h2>
            @can('create', App\Models\Voucher::class)
                <span id="addNew"></span>
            @endcan
        </div>
    </x-slot>
    @can('create', App\Models\Voucher::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showVoucherFormModal')">
                {{ $add_button_label }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->vouchers->total() }}
        </span>
    @endteleport

    @if ($this->vouchers->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->vouchers->links() }}</div>
        @endteleport
    @endif

    @livewire('vouchers.voucher-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        <div>
            <x-label for="search">{{ __('messages.search') }}</x-label>
            <x-input id="search" type="text" wire:model.live="filters.search" class="w-full text-start py-0" />
        </div>
        <div>
            <x-label for="date">{{ __('messages.date') }}</x-label>
            <x-input id="date" type="date" wire:model.live="filters.start_date" class="w-full text-start py-0" />
            <x-input type="date" wire:model.live="filters.end_date" class="w-full text-start py-0" />
        </div>
    </div>

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.voucher_number') }}</x-th>
                <x-th>{{ __('messages.manual_id') }}</x-th>
                <x-th>{{ __('messages.date') }}</x-th>
                <x-th>{{ __('messages.notes') }}</x-th>
                <x-th>{{ __('messages.amount') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->vouchers as $voucher)
                <x-tr>
                    <x-td>{{ $voucher->id }}</x-td>
                    <x-td>{{ $voucher->manual_id }}</x-td>
                    <x-td>{!! $voucher->formated_date !!}</x-td>
                    <x-td class=" !whitespace-normal">{{ $voucher->notes }}</x-td>
                    <x-td>{{ $voucher->formated_amount }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">
                            @can('update', $voucher)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showVoucherFormModal',{voucher:{{ $voucher }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan
                            @can('delete', $voucher)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $voucher }})">
                                    <x-svgs.trash class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan
                        </div>
                    </x-td>
                </x-tr>
            @endforeach
        </tbody>
    </x-table>
</div>
