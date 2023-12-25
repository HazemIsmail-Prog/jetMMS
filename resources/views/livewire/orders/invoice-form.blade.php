<div>
    {{-- Invoice Form --}}
    @if (!$showForm)
        <x-button wire:click="$dispatch('showInvoiceForm')">{{ __('messages.create_invoice') }}</x-button>
        <x-section-border />
    @endif
    @if ($showForm)
        @include('livewire.orders.includes.invoice-search-section')
        @include('livewire.orders.includes.invoice-form-section')
        <x-section-border />
    @endif
</div>
