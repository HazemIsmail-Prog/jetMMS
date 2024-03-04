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
    @teleport('#addNew')
        <x-button wire:click="$dispatch('showVoucherFormModal')">
            {{ $add_button_label }}
        </x-button>
    @endteleport

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

    @livewire('accounts.voucher-form')

    <x-input class="w-36 min-w-full text-center py-0" type="text" name="datefilter" value=""
        data-start="filters.start_created_at" data-end="filters.end_created_at"
        placeholder="{{ __('messages.date') }}" />

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.voucher_number') }}</x-th>
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
                        <x-td>{{ $voucher->date->format('d-m-Y') }}</x-td>
                        <x-td>{{ $voucher->notes }}</x-td>
                        <x-td>{{ number_format($voucher->amount, 3) }}</x-td>
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
</div>