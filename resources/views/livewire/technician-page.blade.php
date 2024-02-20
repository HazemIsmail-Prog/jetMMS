<div>
    @if ($this->order)
        <div class="flex flex-col h-full gap-2">
            <div class=" px-2 border dark:border-gray-700 dark:text-gray-300 rounded-lg">
                <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                    <x-svgs.hash class="w-4 h-4 shrink-0" />
                    <p>{{ str_pad($this->order->id, 8, '0', STR_PAD_LEFT) }}</p>
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

            @livewire('orders.comments.form', ['order' => $this->order], key('order-comments-' . $this->order->id))


            @if (in_array($this->order->status_id, [4, 7]))
                {{-- Existing Invoices --}}
                @if ($this->order->invoices->count() > 0)
                    <div class=" flex flex-col gap-3">
                        @foreach ($this->order->invoices as $invoice)
                            <livewire:orders.invoice-card :$invoice :key="'invoice-' . $invoice->id . '-' . now()">
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center font-bold text-red-600 p-2">
                        {{ __('messages.no_invoices_found') }}
                    </div>
                @endif
                @livewire('orders.invoice-form', ['order' => $this->order], key('order-invoices-' . $this->order->id))
            @endif
        </div>
    @else
        <p class="text-center">{{ __('messages.no_orders') }}</p>
    @endif

</div>

@push('scripts')
    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });
        var channel = pusher.subscribe("RefreshTechnicianPageChannel{{ auth()->id() }}");
        channel.bind("App\\Events\\RefreshTechnicianPageEvent", (data) => {
            livewire.emit('order_updated');
        });
    </script>
@endpush
