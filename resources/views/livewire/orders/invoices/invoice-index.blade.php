<div>
    @if ($order)
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
</div>
