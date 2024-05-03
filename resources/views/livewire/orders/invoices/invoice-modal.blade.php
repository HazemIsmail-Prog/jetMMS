<div>
    <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
        <x-slot name="content">
            @if ($order)
                @livewire('orders.invoices.invoice-index', ['order' => $order], key($order->id . rand()))
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
