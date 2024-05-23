@props(['order'])

@php
    if($order->estimated_start_date > today())
    {
        $color = '#000000';
    }else{
        $color = $order->status_color;
    }
@endphp

<div 
{{-- x-on:dblclick="$dispatch('showDetailsModal',{order:{{$order->id}}})" --}}
wire:key="order-{{ $order->id . rand() }}" id="order-{{ $order->id }}" data-index="{{ $order->index }}" class="
    {{ $order->in_progress ? '' : 'draggable' }}
    {{-- {{ $order->unread_comments_count > 0 ? ' animate-wiggle hover:animate-none' : '' }} --}}
    order
    border
    py-2 lg:py-0
    p-0 lg:p-0
    {{-- cursor-pointer --}}
    rounded-lg
    " style="border-color: {{ $color }};background: {{ $color }};">

    <div wire:click="$dispatch('showCommentsModal',{order:{!! $order !!}})"
        class="block lg:hidden text-center text-xs font-medium">
        {{ $order->formated_id }}
    </div>
    <div class="p-2 text-white">

        <div class="hidden lg:flex justify-between items-center">
            <div class="text-md font-semibold truncate">{{ $order->customer_name }}</div>
            <div class="text-xs">{{ $order->phone_number }}</div>
        </div>
        <h4 class="hidden lg:block mt-2 text-xs">{{ $order->address->full_address }}</h4>
    </div>

    <div
        class="hidden lg:block mt-0 lg:mt-2 bg-white p-2 rounded-t-lg dark:bg-gray-800  text-gray-950 dark:text-gray-200">

        <div class="items-center my-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700">
            {{ $order->creator_name }}
        </div>

        <div class="items-center my-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700">
            {{ $order->formated_id }}
        </div>

        {{-- <div class="items-center my-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700">
            {{ $order->index }}
        </div> --}}

        <div class="items-center my-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700">
            {!! $order->formated_created_at !!}
        </div>

        @if (!$order->in_progress)
        <div class="flex items-center my-1 bg-gray-100 text-xs font-medium p-0 rounded dark:bg-gray-700">
            <x-select wire:change="dragEnd({{$order->id}}, $event.target.value, null, null)"
                class="technician_select !h-auto text-start border-none w-full focus:ring-0 bg-gray-100 text-xs font-medium p-0 rounded dark:bg-gray-700 ">
                @if ($order->status_id != 2)
                <option value="">---</option>
                @endif
                @foreach ($this->technicians->sortBy('name') as $technician)
                <option @selected($technician->id == $order->technician_id) value="{{ $technician->id }}">
                    {{ $technician->name }}</option>
                @endforeach
            </x-select>
        </div>
        @endif

        @if ($order->order_description)
        <div class="flex items-center my-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700">
            {{ $order->order_description }}
        </div>
        @endif

        @if ($order->notes)
        <div class="flex items-center my-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700">
            {{ $order->notes }}
        </div>
        @endif

    </div>

    <div
        class="hidden lg:flex items-center justify-between w-full mt-px text-xs font-medium bg-white dark:bg-gray-800 p-2 rounded-b-lg text-gray-950 dark:text-gray-200">

        <div class=" flex gap-2 items-center">

            {{-- @can('view_order_progress', $order) --}}
            <x-badgeWithCounter wire:click="$dispatch('showDetailsModal',{order:{!! $order !!}})">
                <x-svgs.list class="h-4 w-4" />
            </x-badgeWithCounter>
            {{-- @endcan --}}

            @if($order->unread_comments_count > 0)
            <x-badgeWithCounter
                class="bg-red-400 border-red-400 dark:border-red-400 text-white hover:bg-red-400 hover:border-red-400 hover:dark:border-red-400 hover:text-white"
                :counter="$order->unread_comments_count"
                {{-- wire:click="$dispatch('showCommentsModal',{order:{!! $order !!}})" --}}
                >
                <x-svgs.comment class="h-4 w-4" />
            </x-badgeWithCounter>
            @endif

            {{-- @if ($order->can_view_order_invoices)
            <x-badgeWithCounter :counter="$order->invoices_count"
                wire:click="$dispatch('showInvoicesModal',{order:{!! $order !!}})">
                <x-svgs.banknotes class="h-4 w-4" />
            </x-badgeWithCounter>
            @endif --}}


        </div>
        <div class=" flex gap-2 items-center">

            @if ($order->invoices_count == 0)                
                @if ($order->can_hold_order)
                <x-badgeWithCounter 
                    wire:confirm="{{__('messages.are_u_sure')}}"
                    wire:click="dragEnd({{ $order->id }}, 'hold', null, null)">
                    <x-svgs.clock class="h-4 w-4" />
                </x-badgeWithCounter>
                @endif

                @can('cancel_order', $order)
                <x-badgeWithCounter
                    wire:click="$dispatch('showCancelReasonModal',{order:{!! $order !!}})">
                    <x-svgs.trash class="h-4 w-4" />
                </x-badgeWithCounter>
                @endcan
            @endif

        </div>

    </div>

</div>