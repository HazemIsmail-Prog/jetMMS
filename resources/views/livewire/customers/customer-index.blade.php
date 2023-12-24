<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.customers') }}
                <span id="counter"></span>
            </h2>
            <x-anchor class="no-print" href="{{ route('customer.form') }}">{{ __('messages.add_customer') }}</x-anchor>
        </div>
    </x-slot>
    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->customers->total() }}
        </span>
    @endteleport

    @if ($this->customers->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->customers->links() }}</div>
        @endteleport
    @endif

    @livewire('orders.order-form')

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th>
                        <x-input placeholder="{{ __('messages.name') }}" wire:model.live="filters.name"
                            class="w-full text-start py-0" />
                    </th>
                    <th class=" text-center">
                        <x-input placeholder="{{ __('messages.phone') }}" wire:model.live="filters.phone"
                            class="w-full py-0" dir="ltr" />
                    </th>
                    <th>
                        <x-select wire:model.live="filters.area_id" class=" w-full py-0">
                            <option value="">{{ __('messages.area') }}</option>
                            @foreach ($this->areas->sortBy('name') as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-input placeholder="{{ __('messages.block') }}" wire:model.live="filters.block"
                            class="w-full py-0" dir="ltr" />
                    </th>
                    <th>
                        <x-input placeholder="{{ __('messages.street') }}" wire:model.live="filters.street"
                            class="w-full py-0" dir="ltr" />
                    </th>
                    <th class=" text-center">{{ __('messages.remaining_amount') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->customers as $customer)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $customer->name }}
                        </th>
                        <td class="px-6 py-1 whitespace-nowrap">
                            @foreach ($customer->phones as $phone)
                                <div>{{ $phone->number }}</div>
                            @endforeach
                        </td>
                        <td colspan="3" class="px-6 py-1 text-start whitespace-nowrap">
                            @foreach ($customer->addresses as $address)
                                <div>{{ $address->full_address }}</div>
                            @endforeach
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $customer->balance > 0 ? number_format($customer->balance, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-end whitespace-nowrap flex items-center gap-2 no-print">

                            <x-badgeWithCounter title="{{ __('messages.add_order') }}"
                                wire:click="$dispatch('showOrderFormModal',{customer:{{ $customer }}})">
                                <x-svgs.plus class="h-4 w-4" />
                            </x-badgeWithCounter>

                            <a title="{{ __('messages.edit') }}"
                                href="{{ route('customer.form', $customer) }}"
                                class="flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                <x-svgs.edit class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
