<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border />
            </x-slot>

            <x-slot name="content">
                @livewire('orders.comments.form', ['order' => $this->order], key($this->order->id))
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
