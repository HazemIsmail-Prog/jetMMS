<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $department->name }}
            <span id="counter"></span>
        </h2>
    </x-slot>

    @livewire('orders.comments.comment-modal')
    @livewire('orders.statuses.status-index')
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

    <div class="grid h-full">
        <div class=" overflow-x-auto text-gray-700 dark:text-slate-400">

            <div class="flex overflow-x-auto  gap-2">
                {{-- unassgined --}}
                <div class="flex flex-col flex-shrink-0 gap-2 w-64 transition-all overflow-hidden">
                    <div class="flex items-center justify-between flex-shrink-0 rounded-md h-10 p-4 bg-gray-50 dark:bg-gray-700">
                        <span class="block text-sm font-semibold uppercase">{{ __('messages.unassigned') }}</span>
                        <span
                            class="bg-gray-100 text-gray-800 border border-gray-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500">
                            {{ $this->orders->where('status_id', 1)->count() }}
                        </span>
                    </div>
                    <div class="box hidden-scrollbar flex flex-col gap-4 overflow-auto p-4 h-[calc(100vh-240px)] rounded-md  border border-gray-200 dark:border-gray-700"
                        id="tech0">
                        @foreach ($this->orders->where('status_id', 1) as $i => $order)
                            @livewire(
                                'dispatching.order-box',
                                [
                                    'order' => $order,
                                    'technicians' => $this->technicians,
                                    'customer_name' => $order->customer_name,
                                    'phone_number' => $order->phone_number,
                                    'status_color' => $order->status_color,
                                    'order_creator' => $order->order_creator,
                                    'comments_count' => $order->comments_count,
                                    'address' => $order->address,
                                    'order_description' => $order->order_description,
                                    'invoices_count' => $order->invoices_count,
                                    'unread_comments_count' => $order->unread_comments_count,
                                ],
                                key($order->id . rand())
                            )
                        @endforeach
                    </div>
                </div>

                {{-- On Hold --}}
                <div class="flex flex-col flex-shrink-0 gap-2 w-64 overflow-hidden">
                    <div
                        class="flex items-center justify-between flex-shrink-0 rounded-md h-10 p-4 bg-gray-50 dark:bg-gray-700">
                        <span class="block text-sm font-semibold uppercase">{{ __('messages.on_hold') }}</span>
                        <span
                            class="bg-gray-100 text-gray-800 border border-gray-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500">
                            {{ $this->orders->where('status_id', 5)->count() }}
                        </span>
                    </div>
                    <div class="box hidden-scrollbar flex flex-col gap-4 overflow-auto p-4 h-[calc(100vh-240px)] rounded-md  border border-gray-200 dark:border-gray-700"
                        id="techhold">
                        @foreach ($this->orders->where('status_id', 5) as $i => $order)
                            @livewire(
                                'dispatching.order-box',
                                [
                                    'order' => $order,
                                    'technicians' => $this->technicians,
                                    'customer_name' => $order->customer_name,
                                    'phone_number' => $order->phone_number,
                                    'status_color' => $order->status_color,
                                    'order_creator' => $order->order_creator,
                                    'comments_count' => $order->comments_count,
                                    'address' => $order->address,
                                    'order_description' => $order->order_description,
                                    'invoices_count' => $order->invoices_count,
                                    'unread_comments_count' => $order->unread_comments_count,
                                ],
                                key($order->id . rand())
                            )
                        @endforeach
                    </div>
                </div>

                {{-- Shifts --}}
                <div
                    class=" hidden-scrollbar flex flex-col items-start h-[calc(100vh-186px)] pb-10 overflow-x-auto gap-2 ">
                    @foreach ($this->technicians->sortBy('shift.start_time')->groupBy('shift_id') as $shift_technicians)
                        <div class=" flex flex-col gap-2">
                            <a href="#shift-{{ $shift_technicians->first()->shift_id }}"
                                id="shift-{{ $shift_technicians->first()->shift_id }}"
                                class=" scroll-smooth flex items-center flex-shrink-0 h-10 p-4 cursor-pointer rounded-md bg-gray-50 dark:bg-gray-700">
                                <span class="block text-sm font-semibold uppercase">
                                    @if ($shift_technicians->first()->shift_id)
                                        {{ $shift_technicians->first()->shift->name }}
                                        {{ __('messages.from') }}
                                        {{ date('h:i', strtotime($shift_technicians->first()->shift->start_time)) }}
                                        {{ __('messages.to') }}
                                        {{ date('h:i', strtotime($shift_technicians->first()->shift->end_time)) }}
                                    @else
                                        {{ __('messages.undefined_shift') }}
                                    @endif
                                </span>
                            </a>

                            <div class=" flex gap-2 flex-1">
                                @foreach ($shift_technicians->sortBy('name') as $technician)
                                    <div class="flex flex-col flex-shrink-0 w-64 gap-2 rounded-md overflow-hidden ">
                                        <div
                                            class="flex items-center justify-between rounded-md cursor-pointer flex-shrink-0 h-10 p-4 bg-gray-50 dark:bg-gray-700">
                                            <span
                                                class="block text-sm font-semibold uppercase">{{ $technician->name }}</span>
                                            <div class=" flex">
                                                <a href="{{ route('order.index', [
                                                    'filters[technicians]' => $technician->id,
                                                    'filters[statuses]' => App\Models\Status::COMPLETED,
                                                    'filters[start_completed_at]' => today()->format('Y-m-d'),
                                                    'filters[end_completed_at]' => today()->format('Y-m-d'),
                                                ]) }}"
                                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-700 dark:text-green-300">
                                                    {{ $technician->todays_completed_orders_count }}
                                                </a>
                                                <span
                                                    class="bg-gray-100 text-gray-800 border border-gray-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500">
                                                    {{ $technician->current_orders_count }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="box hidden-scrollbar flex flex-col gap-4 overflow-auto p-4 h-[calc(100vh-320px)] rounded-md  border border-gray-200 dark:border-gray-700"
                                            id="tech{{ $technician->id }}">
                                            @foreach ($this->orders->where('technician_id', $technician->id) as $i => $order)
                                                @livewire(
                                                    'dispatching.order-box',
                                                    [
                                                        'order' => $order,
                                                        'technicians' => $this->technicians,
                                                        'customer_name' => $order->customer_name,
                                                        'phone_number' => $order->phone_number,
                                                        'status_color' => $order->status_color,
                                                        'order_creator' => $order->order_creator,
                                                        'comments_count' => $order->comments_count,
                                                        'address' => $order->address,
                                                        'order_description' => $order->order_description,
                                                        'invoices_count' => $order->invoices_count,
                                                        'unread_comments_count' => $order->unread_comments_count,
                                                    ],
                                                    key($order->id . rand())
                                                )
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
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
