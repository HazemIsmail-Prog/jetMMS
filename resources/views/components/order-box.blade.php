@props(['order'])

<div wire:key="order-{{ $order->id }}" id="order-{{ $order->id }}" data-index="{{ $order->index }}"
    class="
    {{ $order->in_progress ? '' : 'draggable' }}
    {{ $order->unread_comments_count > 0 ? ' animate-wiggle hover:animate-none' : '' }}
    order
    card
    border-2
    p-4
    cursor-pointer
        rounded-lg
    "
    style="border-color: {{ $order->status->color }}">

    <div class="flex justify-between items-center">
        <div class="text-md font-semibold">{{ $order->customer->name }}</div>
        <div class="text-xs">{{ $order->phone->number }}</div>
    </div>

    <h4 class="mt-2 text-xs">{{ $order->address->full_address }}</h4>

    <div class="mt-2">

        <div
            class="flex items-center my-1 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400">
            <i class="mgc_user_3_line text-base me-1"></i>
            {{ $order->creator->name }}
        </div>

        <div
            class="flex items-center my-1 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400">
            <i class="mgc_hashtag_line text-base me-1"></i>
            {{ $order->formated_id }}
        </div>

        @if (!$order->in_progress)
            <div x-data={}
                class="flex items-center my-1 bg-gray-100 text-gray-800 text-xs font-medium p-0 rounded dark:bg-gray-700 dark:text-gray-400">
                <x-select @change="$wire.changeTechnician($event.target.value,{{ $order['id'] }})"
                    class="!h-auto border-none w-full focus:ring-0 bg-gray-100 text-gray-800 text-xs font-medium p-0 rounded dark:bg-gray-700 dark:text-gray-400 ">
                    @if ($order->status_id != 2)
                        <option value="">---</option>
                    @endif
                    @foreach ($this->technicians->sortBy('name') as $technician)
                        <option @if ($order->technician_id == $technician->id) selected @endif value="{{ $technician->id }}">
                            {{ $technician->name }}</option>
                    @endforeach
                </x-select>
            </div>
        @endif


        @if ($order->order_description)
            <div
                class="flex items-center my-1 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400">
                <i class="mgc_align_center_line text-base me-1"></i>
                {{ $order->order_description }}
            </div>
        @endif

        @if ($order->notes)
            <div
                class="flex items-center my-1 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400">
                <i class="mgc_attachment_line text-base me-1"></i>
                {{ $order->notes }}
            </div>
        @endif

    </div>

    <div class="flex items-center justify-between w-full mt-3 text-xs font-medium text-gray-400">

        <div class=" flex gap-2 items-center">

            @can('view_order_progress', $order)
                <x-badgeWithCounter wire:click="$dispatch('showStatusHistoryModal',{order:{!! $order !!}})">
                    <x-svgs.list class="h-4 w-4" />
                </x-badgeWithCounter>
            @endcan

            @can('view_order_comments', $order)
                <x-badgeWithCounter :counter="$order->comments_count"
                    wire:click="$dispatch('showCommentsModal',{order:{!! $order !!}})">
                    <x-svgs.comment class="h-4 w-4" />
                </x-badgeWithCounter>
            @endcan

            @if ($order->can_view_order_invoices)
                <x-badgeWithCounter :counter="$order->invoices_count"
                    wire:click="$dispatch('showInvoicesModal',{order:{!! $order !!}})">
                    <x-svgs.banknotes class="h-4 w-4" />
                </x-badgeWithCounter>
            @endif


        </div>
        <div class=" flex gap-2 items-center">

            @if ($order->can_hold_order)
                <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                    wire:click="dragEnd({{ $order->id }},'hold',null,{{ $order->index }})">
                    <x-svgs.clock class="h-4 w-4" />
                </x-badgeWithCounter>
            @endif

            @can('cancel_order', $order)
                <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                    wire:click="dragEnd({{ $order->id }},'cancel',null,{{ $order->index }})">
                    <x-svgs.trash class="h-4 w-4" />
                </x-badgeWithCounter>
            @endcan

        </div>

    </div>

</div>
