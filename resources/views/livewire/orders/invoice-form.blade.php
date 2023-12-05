<div>
    @if ($showForm)
        <div class=" transition-all ease-in-out mb-4">
            {{-- Invoice Form --}}
            @include('livewire.orders.includes.invoice-search-section')
            @include('livewire.orders.includes.invoice-form-section')
            <x-section-border />
        </div>
    @endif
</div>
