<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.orders') }}
                <span id="counter"></span>
            </h2>
            <span id="excel"></span>
        </div>
    </x-slot>

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->orders->total() }}
    </span>
    @endteleport



    @teleport('#excel')
    <div>
        @if ($this->orders->total() <= $maxExportSize) <x-button wire:confirm="{{ __('messages.are_u_sure') }}"
            wire:loading.class=" animate-pulse duration-75 cursor-not-allowed" wire:click="excel"
            wire:loading.attr="disabled">
            <span class="hidden text-red-400 dark:text-red-600" wire:loading.remove.class=" hidden" wire:target="excel">
                {{ __('messages.exporting') }}
            </span>
            <span wire:loading.remove wire:target="excel">{{ __('messages.export_to_excel') }}</span>
            </x-button>
            @else
            <x-button disabled class=" cursor-not-allowed"
                title="{{ __('messages.max_export_size', ['maxExportSize' => $maxExportSize]) }}">{{
                __('messages.export_to_excel') }}</x-button>
            @endif
    </div>
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
    @livewire('customers.customer-form')
    @livewire('orders.comments.comment-modal')
    @livewire('orders.statuses.status-modal')
    @livewire('orders.details.details-modal')
    @livewire('orders.invoices.invoice-modal')
    @livewire('orders.invoices.invoice-form')
    @livewire('orders.invoices.payments.payment-form')
    @livewire('orders.invoices.discount.discount-form')
    {{-- @livewire('orders.cancel-reason-modal') --}}

    {{-- Filters --}}
    <div class=" flex gap-3">
        <div class=" w-3/4">
            <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        
                <div>
                    <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
                    <x-input class="w-36 min-w-full text-center py-0" id="customer_name"
                        wire:model.live="filters.customer_name" />
                </div>
                <div>
                    <x-label for="customer_phone">{{ __('messages.customer_phone') }}</x-label>
                    <x-input dir="ltr" type="number" class="w-36 min-w-full text-center py-0" id="customer_phone"
                        wire:model.live="filters.customer_phone" />
                </div>
                <div>
                    <x-label for="area">{{ __('messages.area') }}</x-label>
                    <x-searchable-select class="!py-[5px]" id="area" :list="$this->areas" wire:model.live="filters.areas"
                        multipule />
                </div>
                <div>
                    <x-label for="block">{{ __('messages.block') }}</x-label>
                    <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="block" wire:model.live="filters.block" />
                </div>
                <div>
                    <x-label for="street">{{ __('messages.street') }}</x-label>
                    <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="street" wire:model.live="filters.street" />
                </div>
            </div>
            <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                <div>
                    <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
                    <x-input id="order_number" wire:model.live="filters.order_number" type="number" dir="ltr"
                        class="w-36 min-w-full text-center py-0" />
                </div>
                <div>
                    <x-label for="creator">{{ __('messages.creator') }}</x-label>
                    <x-searchable-select class="!py-[5px]" id="creator" :list="$this->creators"
                        wire:model.live="filters.creators" multipule />
                </div>
                <div>
                    <x-label for="status">{{ __('messages.status') }}</x-label>
                    <x-searchable-select class="!py-[5px]" id="status" :list="$this->statuses"
                        wire:model.live="filters.statuses" multipule />
                </div>
                <div>
                    <x-label for="technician">{{ __('messages.technician') }}</x-label>
                    <x-searchable-select class="!py-[5px]" id="technician" :list="$this->technicians"
                        wire:model.live="filters.technicians" multipule />
                </div>
                <div>
                    <x-label for="department">{{ __('messages.department') }}</x-label>
                    <x-searchable-select class="!py-[5px]" id="department" :list="$this->departments"
                        wire:model.live="filters.departments" multipule />
                </div>
                <div>
                    <x-label for="tags">{{ __('messages.orderTag') }}</x-label>
                    <x-select class="w-full" id="tags"
                        wire:model.live="filters.tags" >
                    <option value=""></option>
                    @foreach ($this->tags as $tag)
                        <option value="{{ $tag }}">{{ $tag }}</option>
                    @endforeach
                    </x-select>
                </div>
        
            </div>
            <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-3 gap-3">
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
                <div>
                    <x-label for="start_cancelled_at">{{ __('messages.cancelled_at') }}</x-label>
                    <x-input id="start_cancelled_at" class="w-36 min-w-full text-center py-0" type="date"
                        wire:model.live="filters.start_cancelled_at" />
                    <x-input id="end_cancelled_at" class="w-36 min-w-full text-center py-0" type="date"
                        wire:model.live="filters.end_cancelled_at" />
                </div>
        
            </div>
        </div>

        <div class=" border dark:border-gray-700 rounded-lg p-2  w-1/4 hidden-scrollbar h-56 overflow-y-auto">
            <div class="flex flex-col gap-2 items-end">
                @foreach ($this->creatorCounters->sortByDesc('orders_creator_count') as $user)
                <div title="{{ $user->name }}" class="w-full justify-start flex-row-reverse rounded-full overflow-clip bg-gray-200 dark:bg-gray-700 flex items-center">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-2 leading-none  truncate select-none"
                        style="width: {{ ($user->orders_creator_count / $this->creatorCounters->max('orders_creator_count')) * 100 }}%">
                        {{ $user->name }}
                    </div>
                    <span class=" text-center px-2 font-semibold text-xs dark:text-white">{{ $user->orders_creator_count }}</span>
                </div>
                @endforeach
            </div>

        </div>

    </div>



    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.order_number') }}</x-th>
                <x-th>{{ __('messages.created_at') }}</x-th>
                <x-th>{{ __('messages.estimated_start_date') }}</x-th>
                <x-th>{{ __('messages.status') }}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                <x-th>{{ __('messages.technician') }}</x-th>
                <x-th>{{ __('messages.completed_date') }}</x-th>
                <x-th>{{ __('messages.cancel_reason') }}</x-th>
                <x-th>{{ __('messages.customer') }}</x-th>
                {{-- <x-th>{{ __('messages.remaining_amount') }}</x-th> --}}
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->orders as $order)
            <x-tr>
                <x-td>{{ $order->formated_order_id }}</x-td>

                <x-td>
                    <div>{{ $order->created_at->format("d-m-Y") }}</div>
                    <div class=" text-[0.7rem]">{{ $order->created_at->format("H:i") }}</div>
                    <div class="text-[0.7rem] w-20 whitespace-normal">{{ $order->creator->name }}</div>
                </x-td>

                <x-td>{!! $order->formated_estimated_start_date !!}</x-td>

                <x-td style="color: {{ $order->status->color }}">
                    {{ $order->status->name }}
                </x-td>

                <x-td class=" !whitespace-normal">{{ $order->department->name }}</x-td>

                <x-td class=" !whitespace-normal">{{ $order->technician->name ?? '-' }}</x-td>

                <x-td>
                    <div>{{ $order->completed_at ? $order->completed_at->format("d-m-Y") : ($order->cancelled_at ?
                        $order->cancelled_at->format("d-m-Y") : '' )}}</div>
                    <div class="text-[0.7rem]">{{ $order->completed_at ? $order->completed_at->format("H:i") :
                        ($order->cancelled_at ? $order->cancelled_at->format("H:i") : '' )}}</div>
                </x-td>
                <x-td>
                    {{ $order->reason }}
                </x-td>
                <x-td>
                    <div class=" flex items-start gap-1">
                        @can('update', $order->customer)
                        <x-badgeWithCounter title="{{ __('messages.edit') }}"
                            wire:click="$dispatch('showCustomerFormModal',{customer:{{ $order->customer }}})">
                            <x-svgs.edit class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan
                        <div class=" flex-1">
                            <div>{{ $order->customer->name }}</div>
                            <div>{{ $order->phone->number }}</div>
                            <div>{{ $order->address->full_address }}</div>
                            @if ($order->customer->notes)
                            <div class=" text-red-400 font-normal !whitespace-normal">{{ $order->customer->notes }}</div>
                            @endif
                        </div>

                    </div>
                </x-td>
                {{-- <x-td>{{ $order->formated_remaining_amount }}</x-td> --}}
                <x-td>
                    <div class=" flex items-center justify-end gap-2">

                        @if ($order->can_send_survey)
                        <a class=" border dark:border-gray-700 rounded-lg p-1" target="__blank"
                            href="{{ $order->whatsapp_message }}">{{ __('messages.send_survey') }}</a>
                        @endif

                        @can('update', $order)
                        <x-badgeWithCounter title="{{ __('messages.edit') }}"
                            wire:click="$dispatch('showOrderFormModal',{order:{{ $order }},customer:{{ $order->customer }}})">
                            <x-svgs.edit class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        {{-- @can('viewAny', App\Models\Order::class) --}}
                        <x-badgeWithCounter title="{{ __('messages.order_details') }}"
                            wire:click="$dispatch('showDetailsModal',{order:{{ $order }}})">
                            <x-svgs.list class="h-4 w-4" />
                        </x-badgeWithCounter>
                        {{-- @endcan --}}

                        {{-- @can('cancel_order', $order)
                        <x-badgeWithCounter wire:click="$dispatch('showCancelReasonModal',{order:{{ $order }}})">
                            <x-svgs.trash class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan --}}
                        {{--
                        @can('view_order_progress', $order)
                        <x-badgeWithCounter title="{{ __('messages.order_progress') }}"
                            wire:click="$dispatch('showStatusesModal',{order:{{ $order }}})">
                            <x-svgs.list class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @can('view_order_comments', $order)
                        <x-badgeWithCounter :counter="$order->all_comments" title="{{ __('messages.comments') }}"
                            wire:click="$dispatch('showCommentsModal',{order:{{ $order }}})">
                            <x-svgs.comment class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @if ($order->can_view_order_invoices)
                        <x-badgeWithCounter :counter="$order->custom_invoices_count"
                            title="{{ __('messages.invoices') }}"
                            wire:click="$dispatch('showInvoicesModal',{order:{{ $order }}})">
                            <x-svgs.banknotes class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endif

                        --}}
                    </div>
                </x-td>
            </x-tr>
            @endforeach
        </tbody>
    </x-table>
</div>