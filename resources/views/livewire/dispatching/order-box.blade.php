<div id="order-{{ $order->id }}" data-index="{{ $order->index }}"
    class="
        {{ in_array($order->status_id, [3, 7]) ? '' : 'draggable' }} 
        {{ $order->unread > 0 ? ' animate-wiggle hover:animate-none' : '' }} 
        order 
        card 
        border-2
        p-4 
        cursor-pointer
         rounded-lg
        "
    style="border-color: {{ $order->status->colorrrrr }}">

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
            {{ $order->id }}
        </div>

        @if (!in_array($order->status_id, [3, 7]))
            <div
                class="flex items-center my-1 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400">
                <i class="mgc_user_3_line text-base me-1"></i>
                <select wire:model.live="change_order_technician.{{ $order->id }}.technician_id"
                    class="border-none w-full bg-gray-100 text-gray-800 text-xs font-medium ps-0 py-0 rounded dark:bg-gray-700 dark:text-gray-400 ">
                    @if ($order->status_id != 2)
                        <option value="">---</option>
                    @endif
                    @foreach ($this->technicians as $technician)
                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                    @endforeach
                </select>
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

            <x-badgeWithCounter wire:click="$dispatch('showStatusHistoryModal',{order_id:{{ $order->id }}})">
                <x-svgs.list class="h-4 w-4" />
            </x-badgeWithCounter>

            <x-badgeWithCounter :counter="$order->all_comments"
                wire:click="$dispatch('showCommentsModal',{order_id:{{ $order->id }}})">
                <x-svgs.comment class="h-4 w-4" />
            </x-badgeWithCounter>

            <x-badgeWithCounter :counter="$order->invoices_count"
                wire:click="$dispatch('showInvoicesModal',{order_id:{{ $order->id }}})">
                <x-svgs.invoice class="h-4 w-4" />
            </x-badgeWithCounter>

        </div>
        <div class=" flex gap-2 items-center">

            @if ($order->status_id != 5)
                <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                    wire:click="holdOrder({{ $order->id }})">
                    <x-svgs.clock class="h-4 w-4" />
                </x-badgeWithCounter>
            @endif

            <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                wire:click="cancelOrder({{ $order->id }})">
                <x-svgs.trash class="h-4 w-4" />
            </x-badgeWithCounter>

        </div>

    </div>

</div>
