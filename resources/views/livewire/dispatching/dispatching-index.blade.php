<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
            {{ $department->name }}
            <span id="counter"></span>
        </h2>
    </x-slot>

    @livewire('orders.hold-or-cancel-reason-modal')
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

    <div x-data="{
                    hiddenContainers: JSON.parse(localStorage.getItem('hiddenContainers{{ $department->id }}') || '[]'),
                    alpineLoaded: false
                }" x-init="() => {
                alpineLoaded = true;
                $watch('hiddenContainers', value => {
                    localStorage.setItem('hiddenContainers{{ $department->id }}', JSON.stringify(value));
                });
            }"
        class="relative flex gap-1 h-[calc(100dvh-174px)] text-gray-700 dark:text-slate-400">

        <div x-show="!alpineLoaded">
            <x-loading-spinner class=" absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" />
        </div>


        {{-- Hidden Container --}}
        <div x-cloak x-show="hiddenContainers.length > 0" class="h-full w-24 shrink-0 flex flex-col gap-1 ">
            <div @click="hiddenContainers= []"
                class="flex items-center justify-between rounded-md cursor-pointer h-10 p-4 bg-gray-300 dark:bg-gray-700">
                <span class="block text-sm font-semibold uppercase truncate">{{ __('messages.hidden') }}</span>
            </div>
            <div
                class="border border-gray-400 dark:border-gray-700 rounded-lg flex-1 p-2 flex flex-col overflow-y-auto gap-2 hidden-scrollbar">
                <template x-for="(item) in hiddenContainers" :key="item.id">
                    <div class=" select-none rounded-md cursor-pointer text-xs text-center font-semibold p-2 bg-gray-300 dark:bg-gray-700"
                        @click="hiddenContainers = hiddenContainers.filter(obj => obj.id !== item.id);"
                        x-text="item.title">
                    </div>
                </template>
            </div>
        </div>


        {{-- Unassigned --}}
        <x-orders-container title="{{ __('messages.unassigned') }}" :list="$this->unAssaignedOrders" box_id="tech0" />

        {{-- On Hold --}}
        <x-orders-container title="{{ __('messages.on_hold') }}" :list="$this->onHoldOrders" box_id="techhold" />

        {{-- Shifts --}}
        <div class="flex gap-1 flex-1 h-full overflow-y-auto hidden-scrollbar scroll-smooth">

            {{-- Null Shift --}}
            @if ($this->technicians->where('shift_id', null)->count() >= 1)
            <x-shift-container title="{{ __('messages.undefined_shift') }}"
                :list="$this->technicians->where('shift_id', null)" :shift_id="null" />
            @endif

            {{-- Existing Shifts --}}
            @foreach ($this->shifts as $shift)
            @if ($this->technicians->where('shift_id', $shift->id)->count() >= 1)
            <x-shift-container title="{{ $shift->full_name }}" :list="$this->technicians->where('shift_id', $shift->id)"
                :shift_id="$shift->id" />
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
            const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body
                .clientWidth;
            const delay = screenWidth < 1024 ? 500 :
                0; // If less than 1024 pixels, set delay to 500, otherwise set to 0

            sortable = new Sortable.create(element, {
                group: 'box', // set both lists to same group
                draggable: ".draggable", // Specifies which items inside the element should be draggable
                swapThreshold: 1,
                animation: 150,
                delay: delay, // to avoid dragging for touch screen
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