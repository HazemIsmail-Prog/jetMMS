{{-- <div>
    <pre>

        {{ $order }}
    </pre>
</div> --}}



<div x-data={showBox:@entangle('showBox')} x-show="showBox" id="order-{{ $order->id }}" data-index="{{ $order->index }}"
    class="
    {{ in_array($order->status_id, [3, 7]) ? '' : 'draggable' }}
    {{ $animate ? ' animate-wiggle hover:animate-none' : '' }}
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

        @if (!in_array($order->status_id, [3, 7]))
            <div
                class="flex items-center my-1 bg-gray-100 text-gray-800 text-xs font-medium p-0 rounded dark:bg-gray-700 dark:text-gray-400">
                <x-select wire:model.live="technician_id"
                    class="!h-auto border-none w-full focus:ring-0 bg-gray-100 text-gray-800 text-xs font-medium p-0 rounded dark:bg-gray-700 dark:text-gray-400 ">
                    @if ($order->status_id != 2)
                        <option value="">---</option>
                    @endif
                    @foreach ($technicians->sortBy('name') as $technician)
                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
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
                <x-badgeWithCounter wire:click="$dispatch('showStatusHistoryModal',{order:{{ $order }}})">
                    <x-svgs.list class="h-4 w-4" />
                </x-badgeWithCounter>
            @endcan

            @can('view_order_comments', $order)
                <x-badgeWithCounter :counter="$order->comments_count"
                    wire:click="$dispatch('showCommentsModal',{order:{{ $order }}})">
                    <x-svgs.comment class="h-4 w-4" />
                </x-badgeWithCounter>
            @endcan

            @if (in_array($order->status_id, [App\Models\Status::ARRIVED]))
                @can('view_order_invoices', $order)
                    <x-badgeWithCounter :counter="$order->invoices_count"
                        wire:click="$dispatch('showInvoicesModal',{order:{{ $order }}})">
                        <x-svgs.invoice class="h-4 w-4" />
                    </x-badgeWithCounter>
                @endcan
            @endif


        </div>
        <div class=" flex gap-2 items-center">

            @if ($order->status_id != 5)
                @can('hold_order', $order)
                    <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}" wire:click="holdOrder">
                        <x-svgs.clock class="h-4 w-4" />
                    </x-badgeWithCounter>
                @endcan
            @endif

            @can('cancel_order', $order)
                <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}" wire:click="cancelOrder">
                    <x-svgs.trash class="h-4 w-4" />
                </x-badgeWithCounter>
            @endcan

        </div>

    </div>

</div>
