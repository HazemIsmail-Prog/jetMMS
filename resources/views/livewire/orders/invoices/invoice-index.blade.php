<div>
    <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
        <x-slot name="title">
            <div class=" flex items-center justify-between">
                <div>{{ $modalTitle }}</div>
                <x-button type="button"
                    wire:click="$dispatch('showInvoiceFormModal',{order:{{ $order }}})">{{ __('messages.create_invoice') }}</x-button>
            </div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                @if ($this->invoices->count() > 0)
                    <div class=" flex flex-col gap-3">
                        @foreach ($this->invoices as $invoice)
                            <livewire:orders.invoices.invoice-card :$invoice :key="'invoice-' . $invoice->id . '-' . rand()">
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
