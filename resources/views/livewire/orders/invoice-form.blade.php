<div>
    @if ($showForm)
        <div wire:transition class="mb-4">
            {{-- Invoice Form --}}
            @include('livewire.orders.includes.invoice-search-section')
            @include('livewire.orders.includes.invoice-form-section')
            <x-section-border />
        </div>
    @endif
</div>
