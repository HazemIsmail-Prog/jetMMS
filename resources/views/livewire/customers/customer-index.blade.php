<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.customers') }}
                <span id="counter"></span>
            </h2>
            @can('create', App\Models\Customer::class)
                <x-anchor class="no-print" href="{{ route('customer.form') }}">{{ __('messages.add_customer') }}</x-anchor>
            @endcan
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
        <x-table>
            <x-thead>
                <tr>
                    <x-th>
                        <x-input placeholder="{{ __('messages.name') }}" wire:model.live="filters.name"
                            class="w-full text-start py-0" />
                    </x-th>
                    <x-th>
                        <x-input placeholder="{{ __('messages.phone') }}" wire:model.live="filters.phone"
                            class="w-full py-0" dir="ltr" />
                    </x-th>
                    <x-th>
                        <x-searchable-select :list="$this->areas" model="filters.area_id" live />
                    </x-th>
                    <x-th>
                        <x-input placeholder="{{ __('messages.block') }}" wire:model.live="filters.block"
                            class="w-full py-0" dir="ltr" />
                    </x-th>
                    <x-th>
                        <x-input placeholder="{{ __('messages.street') }}" wire:model.live="filters.street"
                            class="w-full py-0" dir="ltr" />
                    </x-th>
                    <x-th>{{ __('messages.remaining_amount') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->customers as $customer)
                    <x-tr>
                        <x-th>{{ $customer->name }}</x-th>
                        <x-td>
                            @foreach ($customer->phones as $phone)
                                <div>{{ $phone->number }}</div>
                            @endforeach
                        </x-td>
                        <x-td colspan="3">
                            @foreach ($customer->addresses as $address)
                                <div>{{ $address->full_address }}</div>
                            @endforeach
                        </x-td>
                        <x-td>
                            {{ $customer->balance > 0 ? number_format($customer->balance, 3) : '-' }}
                        </x-td>
                        <x-td>
                            <div class=" flex items-center justify-end">

                                @can('create', App\Models\Order::class)
                                    <x-badgeWithCounter title="{{ __('messages.add_order') }}"
                                        wire:click="$dispatch('showOrderFormModal',{customer:{{ $customer }}})">
                                        <x-svgs.plus class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('update', $customer)
                                    <a title="{{ __('messages.edit') }}" href="{{ route('customer.form', $customer) }}"
                                        class="flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <x-svgs.edit class="w-4 h-4" />
                                    </a>
                                @endcan
                                @can('viewAny', App\Models\Order::class)
                                    <a href="{{ route('order.index', ['customer_id' => $customer->id]) }}">
                                        <x-badgeWithCounter counter="{{ $customer->orders->count() }}">
                                            <x-svgs.list class="h-4 w-4" />
                                        </x-badgeWithCounter>
                                    </a>
                                @endcan
                            </div>

                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
