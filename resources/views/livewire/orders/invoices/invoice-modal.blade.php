<div>
    <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
        <x-slot name="title">
            <div class=" flex items-center justify-between">
                <div>{{ $modalTitle }}</div>
                @can('create',App\Models\Invoice::class)
                <x-button type="button"
                    wire:click="$dispatch('showInvoiceFormModal',{order:{{ $order }}})">{{ __('messages.create_invoice') }}</x-button>
                    
                @endcan
            </div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($order)
                @livewire('orders.invoices.invoice-index', ['order' => $order], key($order->id . rand()))
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
