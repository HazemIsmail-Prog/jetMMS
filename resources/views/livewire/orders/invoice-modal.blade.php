<div>
        <x-dialog-modal maxWidth="2xl" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border/>
            </x-slot>

            <x-slot name="content">
                @if ($order)



                    <livewire:orders.invoice-form :$order :key="'order-' . $order->id . '-' . now()">


                        {{-- Existing Invoices --}}
                        @if ($this->invoices->count() > 0)
                            <div class=" flex flex-col gap-3">
                                @foreach ($this->invoices as $invoice)
                                    <livewire:orders.invoice-card :$invoice :key="'invoice-' . $invoice->id . '-' . now()">
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center justify-center font-bold text-red-600 p-2">
                                {{ __('messages.no_invoices_found') }}
                            </div>
                        @endif

                @endif
            </x-slot>
        </x-dialog-modal>
</div>
