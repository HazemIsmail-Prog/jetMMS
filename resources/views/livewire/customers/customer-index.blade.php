<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.customers') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>
    @can('create', App\Models\Customer::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showCustomerFormModal')">
                {{ __('messages.add_customer') }}
            </x-button>
        @endteleport
    @endcan

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
    @livewire('customers.customer-form')


    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        <div>
            <x-label for="name">{{ __('messages.name') }}</x-label>
            <x-input id="name" placeholder="{{ __('messages.name') }}" wire:model.live="filters.name"
                class="w-full text-start py-0" />
        </div>
        <div>
            <x-label for="phone">{{ __('messages.phone') }}</x-label>
            <x-input id="phone" placeholder="{{ __('messages.phone') }}" wire:model.live="filters.phone"
                class="w-full py-0" dir="ltr" />
        </div>
        <div>
            <x-label for="area">{{ __('messages.area') }}</x-label>
            <x-searchable-select class=" !py-1" id="area" :list="$this->areas" model="filters.area_id" live />
        </div>
        <div>
            <x-label for="block">{{ __('messages.block') }}</x-label>
            <x-input id="block" placeholder="{{ __('messages.block') }}" wire:model.live="filters.block"
                class="w-full py-0" dir="ltr" />
        </div>
        <div>
            <x-label for="street">{{ __('messages.street') }}</x-label>
            <x-input id="street" placeholder="{{ __('messages.street') }}" wire:model.live="filters.street"
                class="w-full py-0" dir="ltr" />
        </div>
    </div>

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.name') }}</x-th>
                    <x-th>{{ __('messages.phone') }}</x-th>
                    <x-th>{{ __('messages.address') }}</x-th>
                    <x-th>{{ __('messages.created_at') }}</x-th>
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
                        <x-td>
                            @foreach ($customer->addresses as $address)
                                <div class=" whitespace-nowrap">{{ $address->full_address }}</div>
                            @endforeach
                        </x-td>
                        <x-td>{{ $customer->fromated_created_at }}</x-td>
                        <x-td>{{ $customer->fromated_balance }}</x-td>
                        <x-td>
                            <div class=" flex items-center justify-end gap-2">
                                @can('create', App\Models\Order::class)
                                    <x-badgeWithCounter title="{{ __('messages.add_order') }}"
                                        wire:click="$dispatch('showOrderFormModal',{customer:{{ $customer }}})">
                                        <x-svgs.plus class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('update', $customer)
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showCustomerFormModal',{customer:{{ $customer }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan
                                @can('viewAny', App\Models\Order::class)
                                    <a href="{{ route('order.index', ['customer_id' => $customer->id]) }}">
                                        <x-badgeWithCounter counter="{{ $customer->orders->count() }}">
                                            <x-svgs.list class="h-4 w-4" />
                                        </x-badgeWithCounter>
                                    </a>
                                @endcan
                                @can('delete', $customer)
                                    <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $customer }})">
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
