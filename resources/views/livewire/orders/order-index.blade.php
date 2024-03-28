<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.orders') }}
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

    @livewire('orders.order-form')
    @livewire('orders.comments.comment-modal')
    @livewire('orders.statuses.status-index')
    @livewire('orders.invoices.invoice-modal')
    @livewire('orders.invoices.invoice-form')
    @livewire('orders.invoices.payments.payment-form')
    @livewire('orders.invoices.discount.discount-form')



    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
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
            <x-label for="area">{{ __('messages.area') }}</x-label>
            <x-searchable-select class=" !py-1" id="area" :list="$this->areas" model="filters.areas" live />
        </div>
        <div>
            <x-label for="block">{{ __('messages.block') }}</x-label>
            <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="block"
                wire:model.live="filters.block" />
        </div>
        <div>
            <x-label for="street">{{ __('messages.street') }}</x-label>
            <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="street"
                wire:model.live="filters.street" />
        </div>
    </div>


    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
        <div>
            <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
            <x-input id="order_number" wire:model.live="filters.order_number" type="number" dir="ltr"
                class="w-36 min-w-full text-center py-0" />
        </div>
        <div>
            <x-label for="creator">{{ __('messages.creator') }}</x-label>
            <x-searchable-select class=" !py-1" id="creator" :list="$this->creators" model="filters.creators" live />
        </div>
        <div>
            <x-label for="status">{{ __('messages.status') }}</x-label>
            <x-searchable-select class=" !py-1" id="status" :list="$this->statuses" model="filters.statuses" live />
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
            <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
            <x-input id="start_created_at" class="w-36 min-w-full text-center py-0" type="date"
                wire:model.live="filters.start_created_at" />
            <x-input id="end_created_at" class="w-36 min-w-full text-center py-0" type="date"
                wire:model.live="filters.end_created_at" />
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
                    <x-th>{{ __('messages.created_at') }}</x-th>
                    <x-th>{{ __('messages.creator') }}</x-th>
                    <x-th>{{ __('messages.estimated_start_date') }}</x-th>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.completed_date') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.customer_name') }}</x-th>
                    <x-th>{{ __('messages.customer_phone') }}</x-th>
                    <x-th>{{ __('messages.address') }}</x-th>
                    <x-th>{{ __('messages.remaining_amount') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->orders as $order)
                    <x-tr>
                        <x-td>{{ $order->formated_order_id }}</x-td>
                        <x-td>{!! $order->formated_created_at !!}</x-td>
                        <x-td>{{ $order->creator->name }}</x-td>
                        <x-td>{!! $order->formated_estimated_start_date !!}</x-td>
                        <x-td style="color: {{ $order->status->color }}">
                            {{ $order->status->name }}
                        </x-td>
                        <x-td>{{ $order->department->name }}</x-td>
                        <x-td>{!! $order->formated_completed_at !!}</x-td>
                        <x-td>{{ $order->technician->name ?? '-' }}</x-td>
                        <x-td>{{ $order->customer->name }}</x-td>
                        <x-td>{{ $order->phone->number }}</x-td>
                        <x-td>{{ $order->address->full_address }}</x-td>
                        <x-td>{{ $order->formated_remaining_amount }}</x-td>
                        <x-td>
                            <div class=" flex items-center justify-end gap-2">

                                @can('update', $order)
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showOrderFormModal',{order:{{ $order }},customer:{{ $order->customer }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('view_order_progress', $order)
                                    <x-badgeWithCounter title="{{ __('messages.order_progress') }}"
                                        wire:click="$dispatch('showStatusHistoryModal',{order:{{ $order }}})">
                                        <x-svgs.list class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('view_order_comments', $order)
                                    <x-badgeWithCounter :counter="$order->all_comments" title="{{ __('messages.comments') }}"
                                        wire:click="$dispatch('showCommentsModal',{order:{{ $order }}})">
                                        <x-svgs.comment class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @if (in_array($order->status_id, [App\Models\Status::ARRIVED, App\Models\Status::COMPLETED]))
                                    @can('view_order_invoices', $order)
                                        <x-badgeWithCounter :counter="$order->custom_invoices_count" title="{{ __('messages.invoices') }}"
                                            wire:click="$dispatch('showInvoicesModal',{order:{{ $order }}})">
                                            <x-svgs.banknotes class="h-4 w-4" />
                                        </x-badgeWithCounter>
                                    @endcan
                                @endif
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
