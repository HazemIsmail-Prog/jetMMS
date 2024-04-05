<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $department->name }}
                <span id="counter"></span>
            </h2>
            <span id="shifts"></span>
        </div>
    </x-slot>

    @livewire('orders.comments.comment-modal')
    @livewire('orders.statuses.status-modal')
    @livewire('orders.invoices.invoice-modal')
    @livewire('orders.invoices.invoice-form')
    @livewire('orders.invoices.payments.payment-form')
    @livewire('orders.invoices.discount.discount-form')

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->orders->count() }}
        </span>
    @endteleport

    @teleport('#shifts')
        <div class=" flex items-center gap-1">
            @if ($this->technicians->where('shift_id', null)->count() > 1)
                <x-button onclick="location.href='#shift-';">
                    <span class="text-sm font-semibold uppercase">
                        {{ __('messages.undefined_shift') }}
                    </span>
                </x-button>
            @endif
            @foreach ($this->shifts as $shift)
                @if ($this->technicians->where('shift_id', $shift->id)->count() > 1)
                    <x-button onclick="location.href='#shift-{{ $shift->id }}';">
                        <span class="text-sm font-semibold uppercase">
                            {{ $shift->name }}
                        </span>
                    </x-button>
                @endif
            @endforeach
        </div>
    @endteleport

    <div class="flex gap-1 h-[calc(100vh-184px)] text-gray-700 dark:text-slate-400">

        {{-- Unassigned --}}
        <x-orders-container title="{{ __('messages.unassigned') }}" :list="$this->unAssaignedOrders" box_id="tech0" />

        {{-- On Hold --}}
        <x-orders-container title="{{ __('messages.on_hold') }}" :list="$this->onHoldOrders" box_id="techhold" />

        {{-- Shifts --}}
        <div class="flex-1 h-full overflow-y-auto hidden-scrollbar scroll-smooth">

            {{-- Null Shift --}}
            @if ($this->technicians->where('shift_id', null)->count() > 1)
                <x-shift-container title="{{ __('messages.undefined_shift') }}" :list="$this->technicians->where('shift_id', null)" :shift_id="null" />
            @endif

            {{-- Existing Shifts --}}
            @foreach ($this->shifts as $shift)
                @if ($this->technicians->where('shift_id', $shift->id)->count() > 1)
                    <x-shift-container title="{{ $shift->full_name }}" :list="$this->technicians->where('shift_id', $shift->id)" :shift_id="$shift->id" />
                @endif
            @endforeach

        </div>
    </div>
</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endassets

@script
    <script>
        box = document.querySelectorAll('.box')
        box.forEach(element => {
            sortable = new Sortable.create(element, {
                group: 'box', // set both lists to same group
                draggable: ".draggable", // Specifies which items inside the element should be draggable
                swapThreshold: 1,
                animation: 150,
                // delay: 500, // to avoid dragging for touch screen
                direction: 'vertical',
                onEnd: function( /**Event*/ evt) {
                    //Apply code only if order change its box or index otherwise do nothing
                    if ((evt.oldIndex !== evt.newIndex) || (evt.to.id !== evt.from.id)) {
                        order_id = evt.item.id.replace('order-', '');
                        destenation_id = evt.to.id.replace('tech', '');
                        source_id = evt.from.id.replace('tech', '');
                        if (evt.item.previousElementSibling) {
                            prev = evt.item.previousElementSibling.getAttribute('data-index');
                        } else {
                            prev = null
                        }
                        if (evt.item.nextElementSibling) {
                            next = evt.item.nextElementSibling.getAttribute('data-index');
                        } else {
                            next = null
                        }
                        if (prev != null && next != null) {
                            new_index = (parseFloat(prev) + parseFloat(next)) / 2;
                            //between two orders
                        } else {
                            if (prev == null && next == null) {
                                //first in blank box
                                new_index = 0;
                            } else {
                                if (prev == null) {
                                    //first with next'
                                    new_index = parseFloat(next) - parseFloat(10);
                                } else {
                                    if (next == null) {
                                        //last with prev
                                        new_index = parseFloat(prev) + parseFloat(10);
                                    }
                                }
                            }
                        }
                        @this.dragEnd(order_id, destenation_id, source_id, new_index);
                    }
                },
            });
        });
    </script>
@endscript
