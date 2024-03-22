<div class="h-full">
    @livewire('orders.invoices.invoice-modal')
    @livewire('orders.invoices.invoice-form')
    @livewire('orders.invoices.payments.payment-form')
    @if ($this->order)
        <div class="flex flex-col h-full gap-2">
            <div class=" px-2 border dark:border-gray-700 dark:text-gray-300 rounded-lg">
                <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                    <x-svgs.hash class="w-4 h-4 shrink-0" />
                    <p>{{ $this->order->formated_id }}</p>
                </div>
                <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                    <x-svgs.user class="w-4 h-4 shrink-0" />
                    <p>{{ $this->order->customer->name }}</p>
                </div>
                @if ($this->order->status_id != 2)
                    <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.phone class="w-4 h-4 shrink-0" />
                        <p>{{ $this->order->phone->number }}</p>
                        <a class=" ms-5" target="_blank" href="https://wa.me/+965{{ $this->order->phone->number }}">
                            <x-svgs.whatsapp class="w-4 h-4 shrink-0" />
                        </a>
                    </div>
                    <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.map-pen class="w-4 h-4 shrink-0" />
                        <p>{{ $this->order->address->full_address }}</p>
                        <a class=" ms-5" target="_blank" href="{{ $this->order->address->maps_search() }}">
                            <x-svgs.map-pen class="w-4 h-4 shrink-0" />
                        </a>
                    </div>
                @endif

                @if ($this->order->order_description)
                    <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.list class="w-4 h-4 shrink-0" />
                        <p>{{ $this->order->order_description }}</p>
                    </div>
                @endif
                @if ($this->order->notes)
                    <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.list class="w-4 h-4 shrink-0" />
                        <p>{{ $this->order->notes }}</p>
                    </div>
                @endif

                <div class="py-2 flex justify-center">
                    @switch($this->order->status_id)
                        @case(2)
                            <x-button wire:click="accept_order" wire:confirm="{{ __('messages.are_u_sure') }}"
                                wire:loading.attr="disabled" class="">{{ __('messages.accept') }}</x-button>
                        @break

                        @case(3)
                            <x-button wire:click="arrived_order" wire:confirm="{{ __('messages.are_u_sure') }}"
                                wire:loading.attr="disabled" class="">{{ __('messages.arrived') }}</x-button>
                        @break

                        @case(7)
                            <x-button :disabled="$this->order->invoices->count() == 0 && config('global.invoice_required')" {{-- {{ $this->order->invoices->count() == 0 && config('global.invoice_required') ? 'disabled' : '' }}  --}} wire:click="complete_order"
                                wire:confirm="{{ __('messages.are_u_sure') }}" wire:loading.attr="disabled"
                                class="">{{ __('messages.done') }}</x-button>
                        @break
                    @endswitch
                </div>

            </div>

            @livewire('orders.comments.comment-form', ['order' => $this->order], key('order-comments-' . $this->order->id))


            @if (in_array($this->order->status_id, [4, 7]))
                <x-button type="button" class=" flex justify-center"
                    wire:click="$dispatch('showInvoicesModal',{order:{{ $this->order }}})">{{ __('messages.invoices') }}</x-button>
            @endif
        </div>
    @else
    <div class="flex items-center justify-center min-h-full">

        <p class="text-gray-700 dark:text-gray-300">{{ __('messages.no_orders') }}</p>
    </div>
    @endif

</div>
