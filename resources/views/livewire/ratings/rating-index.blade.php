<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.ratings') }}
                <span id="counter"></span>
            </h2>
        </div>
    </x-slot>
    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->orders->total() }}
        </span>
    @endteleport

    @if ($this->orders->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->orders->links() }}</div>
        @endteleport
    @endif

    @livewire('ratings.rating-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
        <div>
            <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
            <x-input class="w-36 min-w-full text-center py-0" id="customer_name"
                wire:model.live="filters.customer_name" />
        </div>
        <div>
            <x-label for="customer_phone">{{ __('messages.customer_phone') }}</x-label>
            <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="customer_phone"
                wire:model.live="filters.customer_phone" />
        </div>
        <div>
            <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
            <x-input id="order_number" wire:model.live="filters.order_number" type="number" dir="ltr"
                class="w-36 min-w-full text-center py-0" />
        </div>
        <div>
            <x-label for="technician">{{ __('messages.technician') }}</x-label>
            <x-searchable-select class=" !py-1" id="technician" :list="$this->technicians" model="filters.technicians" live />
        </div>
        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-searchable-select class=" !py-1" id="department" :list="$this->departments" model="filters.departments" live />
        </div>
        <div>
            <x-label for="start_completed_at">{{ __('messages.completed_at') }}</x-label>
            <x-input id="start_completed_at" class="w-36 min-w-full text-center py-0" type="date"
                wire:model.live="filters.start_completed_at" />
            <x-input id="end_completed_at" class="w-36 min-w-full text-center py-0" type="date"
                wire:model.live="filters.end_completed_at" />
        </div>


    </div>

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.order_number') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.customer_name') }}</x-th>
                    <x-th>{{ __('messages.customer_phone') }}</x-th>
                    <x-th>{{ __('messages.completed_date') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th>{{ __('messages.rating') }}</x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->orders as $order)
                    <x-tr>
                        <x-td>{{ $order->formated_order_id }}</x-td>
                        <x-td class=" whitespace-nowrap">{{ $order->department->name }}</x-td>
                        <x-td class=" whitespace-nowrap">{{ $order->technician->name ?? '-' }}</x-td>
                        <x-td class=" whitespace-nowrap">{{ $order->customer->name }}</x-td>
                        <x-td class=" whitespace-nowrap">{{ $order->phone->number }}</x-td>
                        <x-td class=" whitespace-nowrap">{!! $order->formated_completed_at !!}</x-td>
                        <x-td class=" whitespace-nowrap">{{ @$order->rating->notes }}</x-td>
                        <x-td>
                            <div class=" flex items-center justify-start gap-2">

                                @can('create', App\Models\Rating::class)
                                    <x-badgeWithCounter :counter="@$order->rating->rating" title="{{ __('messages.rating') }}"
                                        wire:click="$dispatch('showRatingFormModal',{order:{{ $order }}})">
                                        <x-svgs.star
                                            class="h-4 w-4 text-yellow-400 {{ $order->rating ? 'fill-yellow-400' : ' fill-none' }}" />
                                    </x-badgeWithCounter>
                                @else
                                    <x-badgeWithCounter :counter="@$order->rating->rating" title="{{ __('messages.rating') }}">
                                        <x-svgs.star
                                            class="h-4 w-4 text-yellow-400 {{ $order->rating ? 'fill-yellow-400' : ' fill-none' }}" />
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
