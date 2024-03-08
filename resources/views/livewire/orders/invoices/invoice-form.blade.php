<div>
    <x-dialog-modal maxWidth="xl" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ __('messages.create_invoice') }}</div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                @include('livewire.orders.invoices.includes.invoice-search-section')
                @include('livewire.orders.invoices.includes.invoice-form-section')
                <x-section-border />
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
