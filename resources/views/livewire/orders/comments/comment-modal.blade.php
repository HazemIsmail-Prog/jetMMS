<div>
    <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($order)
                @livewire('orders.comments.comment-form', ['order' => $order], key($order->id . rand()))
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
