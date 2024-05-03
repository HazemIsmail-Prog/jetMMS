<div class=" border dark:border-gray-700 rounded-lg p-3">
    <div class=" flex items-center justify-between">
        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('messages.invoices') }}
        </div>
        @can('create',App\Models\Invoice::class)
        <x-button type="button"
            wire:click="$dispatch('showInvoiceFormModal',{order:{{ $order }}})">{{ __('messages.create_invoice') }}</x-button>
        @endcan
    </div>
    <x-section-border />
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
