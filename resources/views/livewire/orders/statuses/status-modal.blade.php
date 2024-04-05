<div>
    <x-dialog-modal maxWidth="4xl" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($order)
                @livewire('orders.statuses.status-index', ['order' => $order], key($order->id . rand()))
            @endif
        </x-slot>
    </x-dialog-modal>
</div>